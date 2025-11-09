CREATE DATABASE IF NOT EXISTS ai_task_mgmt
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE ai_task_mgmt;

CREATE TABLE users (
  id            BINARY(16) PRIMARY KEY,
  full_name     VARCHAR(150) NOT NULL,
  email         VARCHAR(150) NOT NULL UNIQUE,
  phone         VARCHAR(50),
  position      VARCHAR(120),
  password_hash VARCHAR(255) NOT NULL,
  role          ENUM('Admin','HR','Manager','Employee') NOT NULL DEFAULT 'Employee',
  is_active     TINYINT(1) NOT NULL DEFAULT 1,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE departments (
  id           BINARY(16) PRIMARY KEY,
  name         VARCHAR(120) NOT NULL,
  description  TEXT,
  parent_id    BINARY(16) NULL,
  is_active    TINYINT(1) NOT NULL DEFAULT 1,
  CONSTRAINT fk_dept_parent FOREIGN KEY (parent_id) REFERENCES departments(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE user_departments (
  user_id      BINARY(16) NOT NULL,
  dept_id      BINARY(16) NOT NULL,
  role_in_dept VARCHAR(80),
  is_primary   TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (user_id, dept_id),
  CONSTRAINT fk_ud_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_ud_dept FOREIGN KEY (dept_id) REFERENCES departments(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE job_descriptions (
  id            BINARY(16) PRIMARY KEY,
  title         VARCHAR(180) NOT NULL,
  department_id BINARY(16) NULL,
  user_id       BINARY(16) NULL,
  body_md       MEDIUMTEXT NOT NULL,
  version       INT NOT NULL DEFAULT 1,
  is_active     TINYINT(1) NOT NULL DEFAULT 1,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_jd_dept (department_id, is_active),
  INDEX idx_jd_user (user_id, is_active),
  CONSTRAINT fk_jd_dept FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL,
  CONSTRAINT fk_jd_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE jd_change_requests (
  id               BINARY(16) PRIMARY KEY,
  target_jd_id     BINARY(16) NOT NULL,
  requested_by_id  BINARY(16) NOT NULL,
  proposed_body_md MEDIUMTEXT NOT NULL,
  status           ENUM('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  reviewer_id      BINARY(16) NULL,
  review_note      TEXT NULL,
  created_at       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  resolved_at      DATETIME NULL,
  CONSTRAINT fk_jdcr_jd FOREIGN KEY (target_jd_id) REFERENCES job_descriptions(id) ON DELETE CASCADE,
  CONSTRAINT fk_jdcr_user FOREIGN KEY (requested_by_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE documents (
  id            BINARY(16) PRIMARY KEY,
  title         VARCHAR(200) NOT NULL,
  department_id BINARY(16) NULL,
  user_id       BINARY(16) NULL,
  file_path     VARCHAR(300) NOT NULL,
  tags          VARCHAR(400),
  version       INT NOT NULL DEFAULT 1,
  is_active     TINYINT(1) NOT NULL DEFAULT 1,
  created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_doc_tags (tags),
  CONSTRAINT fk_doc_dept FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL,
  CONSTRAINT fk_doc_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE faqs (
  id            BINARY(16) PRIMARY KEY,
  question      VARCHAR(400) NOT NULL,
  answer        MEDIUMTEXT NOT NULL,
  department_id BINARY(16) NULL,
  popularity    INT NOT NULL DEFAULT 0,
  CONSTRAINT fk_faq_dept FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE ai_sessions (
  id           BINARY(16) PRIMARY KEY,
  user_id      BINARY(16) NOT NULL,
  context_hint VARCHAR(400),
  created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_ais_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE ai_messages (
  id          BINARY(16) PRIMARY KEY,
  session_id  BINARY(16) NOT NULL,
  sender      ENUM('User','AI') NOT NULL,
  text        MEDIUMTEXT NOT NULL,
  tokens_in   INT DEFAULT 0,
  tokens_out  INT DEFAULT 0,
  cost_usd    DECIMAL(12,6) DEFAULT 0,
  created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_msgs_session (session_id, created_at),
  CONSTRAINT fk_aim_session FOREIGN KEY (session_id) REFERENCES ai_sessions(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE audit_logs (
  id         BINARY(16) PRIMARY KEY,
  user_id    BINARY(16) NULL,
  action     VARCHAR(100) NOT NULL,
  entity     VARCHAR(60) NOT NULL,
  entity_id  BINARY(16) NULL,
  before_json JSON NULL,
  after_json  JSON NULL,
  ip         VARCHAR(64),
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
