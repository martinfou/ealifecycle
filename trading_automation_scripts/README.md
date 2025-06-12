# Trading Automation Scripts

This directory contains Python scripts for automating trading tasks, such as managing strategies and deploying them to MetaTrader platforms.

## Overview

The scripts here are designed to interact with the main Laravel application's API and potentially other third-party services. They are intended to be run from the command line.

## Setup

1.  **Navigate to this directory:**
    ```bash
    cd trading_automation
    ```

2.  **Create a Python virtual environment:**
    ```bash
    python3 -m venv venv
    ```

3.  **Activate the virtual environment:**
    *   On macOS and Linux:
        ```bash
        source venv/bin/activate
        ```
    *   On Windows:
        ```bash
        .\venv\Scripts\activate
        ```

4.  **Install the required dependencies:**
    ```bash
    pip install -r requirements.txt
    ```

## Usage

Instructions for running specific scripts will be added here as they are developed.

### Example

```bash
python src/deploy_strategy.py --strategy-id 123 --environment production
```

## Configuration

Configuration files for different environments (e.g., `development.ini`, `production.ini`) are stored in the `config/` directory. These files should contain API keys, server addresses, and other sensitive or environment-specific data. 