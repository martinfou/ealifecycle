# REST API - TODO List

This document outlines the remaining tasks for the development of the V1 REST API.

## Core Functionality

- [ ] **Strategies**
    - [ ] Implement `update` method in `StrategyController`
    - [ ] Implement `destroy` method in `StrategyController`
- [ ] **Portfolios**
    - [ ] Implement `store` method in `PortfolioController`
    - [ ] Implement `update` method in `PortfolioController`
    - [ ] Implement `destroy` method in `PortfolioController`
- [ ] **Source Code Management**
    - [ ] Implement `uploadSourceCode` method in `StrategyController`
    - [ ] Implement `downloadSourceCode` method in `StrategyController`

## Authorization

- [ ] Ensure robust authorization checks are in place for all `store`, `update`, and `destroy` methods to verify user permissions.

## Documentation & Testing

- [ ] **API Documentation:**
    - [ ] Generate or write comprehensive documentation for all API endpoints, including required parameters, expected responses, and authentication details.
- [ ] **Testing:**
    - [ ] Write feature tests for all API endpoints to ensure they are working correctly and are secure.
    - [ ] Test edge cases, validation, and authorization failures.
