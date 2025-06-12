# MT4 Strategy Deployment Automation

This script automates the process of deploying a trading strategy (an Expert Advisor, or `.ex4` file) from a REST API to a local MetaTrader 4 (MT4) instance.

## How It Works

1.  **Downloads Strategy**: Fetches the `.ex4` strategy file from a specified REST API endpoint.
2.  **Finds MT4 Path**: Automatically attempts to locate the MT4 data directory on your system (macOS, Windows, or Linux).
3.  **Installs Strategy**: Copies the downloaded file into the `MQL4/Experts` directory, making it available within the MT4 terminal.
4.  **Requires Manual Refresh**: Notifies the user that they need to refresh the "Expert Advisors" list in MT4 or restart the terminal to see the new strategy.

## Setup and Usage

### 1. Prerequisites

-   A running MetaTrader 4 instance.
-   Python 3.10 or newer is recommended.

### 2. Installation

A Python virtual environment is the recommended way to manage dependencies.

1.  **Create a virtual environment:**
    ```bash
    python3 -m venv venv
    ```

2.  **Install required packages:**
    ```bash
    # On macOS/Linux
    venv/bin/pip install -r requirements.txt

    # On Windows
    .\\venv\\Scripts\\pip install -r requirements.txt
    ```

### 3. Configuration

Open the `deploy_strategy.py` script and configure the following variables at the top of the file:

-   `STRATEGY_API_URL`: The full URL to your REST API endpoint for downloading the strategy file.
-   `API_AUTH_TOKEN`: Your API authentication token. It's best to set this as an environment variable for security.
-   `MT4_DATA_PATH`: The script tries to find this automatically. If it fails, you must set this path manually. See the script's comments for examples of what this path looks like on different operating systems.

### 4. Running the Script

Activate the virtual environment first, then run the script.

```bash
# On macOS/Linux
source venv/bin/activate

# On Windows
.\\venv\\Scripts\\activate

# Run the script
python deploy_strategy.py
```

The script will download the strategy, install it, and print instructions for you to complete the final step in MetaTrader 4. 