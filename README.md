# ThreadWear SQL Injection Security Assessment

A web application security project focused on identifying, exploiting, analyzing, remediating, and retesting a SQL Injection vulnerability in a self-hosted ThreadWear web application.

The project demonstrates a complete ethical hacking workflow, starting from reconnaissance and vulnerability identification through controlled exploitation, impact analysis, secure code remediation, and final verification.

---

## Project Overview

ThreadWear is a locally hosted web application used as a controlled environment for web application security testing.

The main objective of this project was to assess how insecure database query construction could introduce SQL Injection vulnerabilities and demonstrate the potential security impact of such a weakness.

The assessment focused primarily on the application's product search functionality, where user-controlled input was passed to the backend database.

Testing showed that unsafe SQL query construction allowed the application to interpret user input as part of the SQL query rather than normal data.

---

## Objectives

The main objectives of this project were to:

- Understand the technical causes of SQL Injection
- Identify vulnerable application input points
- Analyse application behaviour using controlled test input
- Confirm SQL Injection vulnerability
- Determine the structure of the backend database query
- Assess possible information exposure
- Evaluate the impact of compromised database access
- Apply secure coding remediation
- Retest the application after remediation
- Document the findings using a structured penetration testing approach

---

## Scope

The assessment was performed only against a locally hosted and self-controlled ThreadWear application.

### In Scope

- Login functionality
- Customer dashboard
- Product search functionality
- Database interaction through the search parameter
- User and role information
- Administrative functionality
- Security remediation and retesting

### Out of Scope

- Public websites
- Third-party services
- External organizations
- Production systems
- Real customer information
- External network infrastructure

---

## Assessment Methodology

The security assessment followed a structured penetration testing workflow.

```text
Planning and Scope
        ↓
Reconnaissance
        ↓
Vulnerability Identification
        ↓
Controlled Exploitation
        ↓
Impact Analysis
        ↓
Remediation
        ↓
Retesting
```

---

## 1. Reconnaissance

The first stage involved understanding the normal behaviour of the ThreadWear application.

The product search functionality was examined using normal search terms.

During testing, it was observed that search input was submitted to the server through a URL parameter and influenced the products returned by the backend.

This parameter was selected for further security testing.

---

## 2. Vulnerability Identification

The search functionality was tested using unexpected input to determine whether user-controlled data could influence the SQL query.

Different application responses indicated that the search input was being inserted into a backend SQL statement without sufficient separation between user data and query logic.

Further testing confirmed that the application was vulnerable to SQL Injection.

---

## 3. Query Structure Analysis

After confirming the vulnerability, the next step was to understand the structure of the original SQL query.

Testing was performed to determine:

- Number of columns returned by the original query
- Visible output columns
- Whether additional database output could be displayed through the application

The results demonstrated that injected database output could be displayed inside the product search results.

---

## 4. Database Information Exposure

The vulnerability allowed controlled access to database metadata.

During the assessment, the following types of information were demonstrated:

- Current database name
- Database user
- Database software version
- Available database names
- Table names
- Column names
- User account records
- Account roles

This demonstrated how a SQL Injection vulnerability could affect the confidentiality of backend application data.

---

## 5. Post-Exploitation Impact

The security impact was evaluated after database information exposure.

The assessment demonstrated that compromised account information could potentially provide access to administrative functionality.

Within the local test environment, administrative access allowed actions such as:

- Modifying user roles
- Changing account status
- Modifying product information
- Changing product pricing
- Accessing administrative controls

| Security Area | Potential Impact |
|---|---|
| Confidentiality | Exposure of account and database information |
| Integrity | Unauthorized modification of application data |
| Access Control | Access to administrative functionality |
| Availability | User accounts could be disabled |
| Business Impact | Product and account information could be manipulated |

---

## Security Severity

The demonstrated finding was evaluated using CVSS during the project.

The lab assessment produced a high-impact result because successful exploitation could affect confidentiality, integrity, access control, administrative functions, and application data.

The project report contains the complete CVSS analysis and impact assessment.

---

## Root Cause

The primary cause of the vulnerability was unsafe SQL query construction.

User-controlled input was directly included in an SQL query instead of being safely separated from the query structure.

Conceptually, the vulnerable approach looked like:

```text
SQL query + user-controlled input
```

This allowed specially crafted input to influence the database query.

---

## Remediation

The vulnerable query was replaced using prepared statements and parameter binding.

The remediation ensures that user input is treated as data rather than executable SQL syntax.

The secure implementation used database functions such as:

```text
prepare()
bind_param()
execute()
```

This separates the SQL query structure from user-controlled values.

---

## Retesting

After remediation, the application was tested again using the same categories of input that had previously affected the SQL query.

The retesting confirmed that:

- Unexpected quote input no longer produced SQL errors
- Boolean manipulation no longer returned unauthorized records
- UNION-based testing no longer exposed database information
- User account information could no longer be extracted through the search field

This confirmed that the SQL Injection vulnerability had been successfully mitigated.

---

## Security Controls Recommended

Additional security improvements include:

- Parameterized database queries
- Input validation
- Password hashing
- Least-privilege database accounts
- Secure error handling
- Application security logging
- Role-based access control
- Regular vulnerability testing
- Dependency and source-code security reviews
- Automated security scanning

---

## Technologies and Concepts

This project involved:

- Web Application Security
- Ethical Hacking
- SQL Injection
- PHP
- MariaDB / SQL
- Database Security
- Authentication and Access Control
- Secure Coding
- Prepared Statements
- Parameter Binding
- CVSS Risk Assessment
- Penetration Testing Methodology
- Vulnerability Remediation
- Security Retesting

---

## Key Security Concepts Demonstrated

### SQL Injection
Demonstrated how unsafe handling of user-controlled input can allow database queries to be manipulated.

### Database Enumeration
Demonstrated how a vulnerable query may expose database metadata such as tables, columns, users, and database information.

### Privilege Impact
Demonstrated how exposed administrative account information could lead to unauthorized administrative access.

### Secure Coding
Replaced unsafe database query construction with prepared statements and parameter binding.

### Retesting
Validated the remediation by repeating the original security tests after the fix.

---

## Project Workflow

```text
ThreadWear Web Application
        |
        v
Product Search Input
        |
        v
Security Testing
        |
        v
SQL Injection Identified
        |
        v
Database Information Exposure
        |
        v
Impact Analysis
        |
        v
Secure Query Implementation
        |
        v
Retesting
        |
        v
Vulnerability Mitigated
```

---

## Evidence

The project documentation contains practical evidence including:

- Normal application behaviour
- Vulnerability identification
- SQL query behaviour analysis
- Database information exposure
- Administrative impact demonstration
- CVSS impact assessment
- Vulnerable source-code example
- Secure source-code implementation
- Retesting results

---

## Suggested Repository Structure

```text
threadwear-sqli-security-assessment/
│
├── README.md
│
├── report/
│   └── ThreadWear_SQL_Injection_Security_Assessment.pdf
│
├── application/
│   └── ThreadWear source code
│
├── screenshots/
│   ├── reconnaissance/
│   ├── vulnerability-identification/
│   ├── exploitation/
│   ├── impact/
│   └── remediation/
│
└── docs/
    └── additional-security-notes.md
```

---

## What I Learned

This project strengthened my understanding of:

- How SQL Injection vulnerabilities occur
- How web applications interact with backend databases
- How to identify suspicious application behaviour
- How SQL Injection can expose database structures and account information
- How a technical vulnerability can lead to wider access-control and business impact
- How to evaluate vulnerability severity
- How to remediate insecure database queries
- Why security testing should include retesting after remediation
- How secure development and penetration testing complement each other

---

## Ethical Testing

All practical testing in this project was conducted against a self-hosted application in a controlled environment.

No third-party website, production environment, external organization, or real user data was targeted.

The project was created for cybersecurity learning, secure development practice, and portfolio demonstration.

---

## Author

**Ayudh Dahal**

Cybersecurity | Networking | Web Application Security


---

## Disclaimer

This repository documents security testing performed only within an authorized and controlled environment.

The techniques discussed should only be used on systems that you own or have explicit permission to test.
