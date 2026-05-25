-- 1. Users (no dependencies)
CREATE TABLE users (
    user_id         INT PRIMARY KEY AUTO_INCREMENT,
    username        VARCHAR(50) NOT NULL UNIQUE,
    email           VARCHAR(255) NOT NULL UNIQUE,
    password_hash   VARCHAR(255) NOT NULL,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_active     DATETIME,
    is_banned       TINYINT(1) DEFAULT 0,
    ban_reason      TEXT,
    is_suspended    TINYINT(1) DEFAULT 0,
    suspended_until DATETIME,
    warning_count   INT DEFAULT 0
);

-- 2. Listings (depends on users)
CREATE TABLE listings (
    listing_id   INT PRIMARY KEY AUTO_INCREMENT,
    seller_id    INT NOT NULL,
    title        VARCHAR(255) NOT NULL,
    description  TEXT,
    price        DECIMAL(10,2) NOT NULL,
    category     VARCHAR(100),
    'condition'    ENUM('new','like_new','good','fair') NOT NULL,
    status       ENUM('active','sold','flagged','removed') DEFAULT 'active',
    created_at   DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (seller_id) REFERENCES users(user_id)
);

-- 3. Transactions (depends on users + listings)
CREATE TABLE transactions (
    transaction_id INT PRIMARY KEY AUTO_INCREMENT,
    listing_id     INT NOT NULL,
    buyer_id       INT NOT NULL,
    seller_id      INT NOT NULL,
    amount         DECIMAL(10,2) NOT NULL,
    status         ENUM('pending','held','complete','disputed','refunded') DEFAULT 'pending',
    created_at     DATETIME DEFAULT CURRENT_TIMESTAMP,
    dispatched_at  DATETIME,
    disputed_at    DATETIME,
    resolved_at    DATETIME,
    resolution_note TEXT,
    FOREIGN KEY (listing_id) REFERENCES listings(listing_id),
    FOREIGN KEY (buyer_id)   REFERENCES users(user_id),
    FOREIGN KEY (seller_id)  REFERENCES users(user_id)
);

-- 4. Reports (depends on users)
CREATE TABLE reports (
    report_id       INT PRIMARY KEY AUTO_INCREMENT,
    reporter_id     INT NOT NULL,
    report_type     ENUM('listing','user','transaction') NOT NULL,
    target_id       INT NOT NULL,
    reason          TEXT NOT NULL,
    status          ENUM('open','under_review','resolved') DEFAULT 'open',
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    resolved_at     DATETIME,
    resolution_note TEXT,
    FOREIGN KEY (reporter_id) REFERENCES users(user_id)
);

-- 5. Reviews (depends on users + transactions)
CREATE TABLE reviews (
    review_id      INT PRIMARY KEY AUTO_INCREMENT,
    transaction_id INT NOT NULL,
    reviewer_id    INT NOT NULL,
    reviewee_id    INT NOT NULL,
    role           ENUM('buyer','seller') NOT NULL,
    rating         TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    body           TEXT,
    created_at     DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (transaction_id) REFERENCES transactions(transaction_id),
    FOREIGN KEY (reviewer_id)    REFERENCES users(user_id),
    FOREIGN KEY (reviewee_id)    REFERENCES users(user_id)
);

-- 6. Admin action log (depends on users)
CREATE TABLE admin_log (
    log_id      INT PRIMARY KEY AUTO_INCREMENT,
    admin_id    INT NOT NULL,
    action_type ENUM('warn','suspend','ban','resolve_report','dismiss_report','flag_listing','remove_listing','clear_flags','release_funds','refund_buyer','request_info','escalate') NOT NULL,
    target_type ENUM('user','listing','report','transaction') NOT NULL,
    target_id   INT NOT NULL,
    note        TEXT,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES users(user_id)
);

-- 7. Listing images (depends on listings)
CREATE TABLE listing_images (
    image_id    INT PRIMARY KEY AUTO_INCREMENT,
    listing_id  INT NOT NULL,
    filename    VARCHAR(255) NOT NULL,
    is_primary  TINYINT(1) DEFAULT 0,
    sort_order  INT DEFAULT 0,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (listing_id) REFERENCES listings(listing_id)
);