USE ecommerce_support;

-- =============================================
-- TRIGGER 1: cập nhật total_product cho Store
-- =============================================
DELIMITER $$

CREATE TRIGGER trg_product_after_insert
AFTER INSERT ON Product
FOR EACH ROW
BEGIN
    UPDATE Store
    SET total_product = (
        SELECT COUNT(*) FROM Product p WHERE p.Store_ID = NEW.Store_ID
    )
    WHERE Store_ID = NEW.Store_ID;
END$$

CREATE TRIGGER trg_product_after_delete
AFTER DELETE ON Product
FOR EACH ROW
BEGIN
    UPDATE Store
    SET total_product = (
        SELECT COUNT(*) FROM Product p WHERE p.Store_ID = OLD.Store_ID
    )
    WHERE Store_ID = OLD.Store_ID;
END$$

-- =============================================
-- TRIGGER 2: cấm chủ shop tự review sản phẩm
-- =============================================
CREATE TRIGGER trg_review_prevent_owner_selfreview
BEFORE INSERT ON Review
FOR EACH ROW
BEGIN
    DECLARE v_seller_id INT;

    SELECT st.Seller_ID
    INTO v_seller_id
    FROM OrderItems oi
    JOIN Product p ON oi.Product_ID = p.Product_ID
    JOIN Store st   ON p.Store_ID   = st.Store_ID
    WHERE oi.Order_ID      = NEW.Order_ID
      AND oi.Order_item_ID = NEW.Order_item_ID
    LIMIT 1;

    IF v_seller_id IS NOT NULL
       AND v_seller_id = NEW.Buyer_user_ID THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Store owner is not allowed to review own product.';
    END IF;
END$$

DELIMITER ;

-- =============================================
-- FUNCTION 1: tổng tiền 1 đơn hàng
-- =============================================
DELIMITER $$

CREATE FUNCTION fn_OrderTotal(p_order_id INT)
RETURNS DECIMAL(15,2)
DETERMINISTIC
READS SQL DATA
BEGIN
    DECLARE v_total DECIMAL(15,2);

    SELECT IFNULL(SUM(oi.Quantity * oi.Unit_price), 0)
    INTO v_total
    FROM OrderItems oi
    WHERE oi.Order_ID = p_order_id;

    RETURN v_total;
END$$

-- =============================================
-- FUNCTION 2: tổng chi tiêu của buyer trong khoảng ngày
-- =============================================
CREATE FUNCTION fn_BuyerTotalInPeriod(
    p_buyer_id  INT,
    p_from_date DATE,
    p_to_date   DATE
)
RETURNS DECIMAL(15,2)
DETERMINISTIC
READS SQL DATA
BEGIN
    DECLARE v_total DECIMAL(15,2);

    IF p_from_date IS NULL OR p_to_date IS NULL OR p_from_date > p_to_date THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Invalid date range in fn_BuyerTotalInPeriod';
    END IF;

    SELECT IFNULL(SUM(oi.Quantity * oi.Unit_price), 0)
    INTO v_total
    FROM Orders o
    JOIN OrderItems oi ON o.Order_ID = oi.Order_ID
    WHERE o.Buyer_ID = p_buyer_id
      AND o.Order_date BETWEEN p_from_date AND DATE_ADD(p_to_date, INTERVAL 1 DAY);

    RETURN v_total;
END$$

DELIMITER ;

-- =============================================
-- PROCEDURE 1: Top N sản phẩm bán chạy của 1 store
-- =============================================
DELIMITER $$

CREATE PROCEDURE sp_GetTopSellingProducts(
    IN p_store_id INT,
    IN p_top_n    INT
)
BEGIN
    IF p_top_n <= 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Top N must be greater than 0';
    END IF;

    IF NOT EXISTS (SELECT 1 FROM Store WHERE Store_ID = p_store_id) THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Store_ID does not exist';
    END IF;

    SELECT 
        p.Product_ID,
        p.Name AS ProductName,
        cat.Name AS CategoryName,
        SUM(oi.Quantity) AS TotalSold,
        SUM(oi.Quantity * oi.Unit_price) AS TotalRevenue
    FROM Product p
    JOIN OrderItems oi ON p.Product_ID = oi.Product_ID
    JOIN Orders o      ON oi.Order_ID  = o.Order_ID
    LEFT JOIN Product_belong_Category pbc ON p.Product_ID = pbc.Product_ID
    LEFT JOIN Category cat                ON pbc.Category_ID = cat.Category_ID 
    WHERE 
        p.Store_ID      = p_store_id
        AND o.Order_status = 'delivered'
    GROUP BY 
        p.Product_ID, p.Name, cat.Name
    ORDER BY 
        TotalSold DESC
    LIMIT p_top_n;
END$$

-- =============================================
-- PROCEDURE 2: Thống kê hiệu suất Customer Support
-- =============================================
CREATE PROCEDURE sp_GetCustomerSupportStats(
    IN p_from_date DATE,
    IN p_to_date   DATE
)
BEGIN
    IF p_from_date IS NULL OR p_to_date IS NULL OR p_from_date > p_to_date THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Invalid date range';
    END IF;

    SELECT
        cs.Custom_user_ID,  -- Sửa thành Custom_user_ID cho khớp bảng
        cs.Specialization,
        COUNT(*) AS TotalTickets,
        SUM(CASE WHEN ss.is_solve = 1 THEN 1 ELSE 0 END) AS SolvedTickets
    FROM Support_session ss
    JOIN Customer_Support cs ON ss.CS_ID = cs.Custom_user_ID -- Join đúng cột khóa
    WHERE ss.created_at BETWEEN p_from_date AND DATE_ADD(p_to_date, INTERVAL 1 DAY)
    GROUP BY cs.Custom_user_ID, cs.Specialization
    ORDER BY SolvedTickets DESC, TotalTickets DESC;
END$$

-- =============================================
-- PROCEDURE 3: Doanh thu tháng (Cập nhật mới)
-- =============================================
CREATE PROCEDURE sp_GetMonthlyRevenue(
    IN p_year INT,
    IN p_month INT
)
BEGIN
    DECLARE v_total_revenue DECIMAL(15,2) DEFAULT 0;
    DECLARE v_product_amount DECIMAL(15,2) DEFAULT 0;
    DECLARE v_extra_fee DECIMAL(15,2) DEFAULT 0;

    SELECT IFNULL(SUM(Amount), 0) INTO v_total_revenue
    FROM Payment
    WHERE YEAR(Payment_time) = p_year 
      AND MONTH(Payment_time) = p_month
      AND Payment_status = 'paid';

    SELECT IFNULL(SUM(oi.Quantity * oi.Unit_price), 0) INTO v_product_amount
    FROM OrderItems oi
    JOIN Payment p ON oi.Order_ID = p.Order_ID
    WHERE YEAR(p.Payment_time) = p_year 
      AND MONTH(p.Payment_time) = p_month
      AND p.Payment_status = 'paid';

    SET v_extra_fee = v_total_revenue - v_product_amount;

    SELECT 
        p_month AS month,
        p_year AS year,
        v_product_amount AS total_product_amount,
        v_extra_fee AS total_extra_fee,
        v_total_revenue AS total_revenue;
END$$

DELIMITER ;