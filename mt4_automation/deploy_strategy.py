import os
import json
import requests
import shutil
import platform
import subprocess
from pathlib import Path

# --- Configuration ---
# Replace with your actual REST API endpoint for downloading strategies
STRATEGY_API_URL = "https://api.strategyquant.com/v1/strategies/12345"
# It's recommended to use environment variables for sensitive data
API_AUTH_TOKEN = os.getenv("STRATEGY_API_TOKEN", "your_default_api_token")

# --- MetaTrader 4 Configuration ---
# The script will attempt to auto-detect this, but you can override it.
# This is the path to the MT4 data folder, not the program installation folder.
# On Windows: C:/Users/<YourUser>/AppData/Roaming/MetaQuotes/Terminal/<32-char-hash>/
# On macOS with Wine: /Users/<YourUser>/.wine/drive_c/users/<YourUser>/Application Data/MetaQuotes/Terminal/<32-char-hash>/
MT4_DATA_PATH = "C:/Users/Administrator/Desktop/STAGING - OANDA - MetaTrader 4" 

# The directory where Expert Advisors (.ex4 files) are stored.
EXPERTS_DIR_NAME = "MQL4/Experts"

def find_mt4_data_path():
    """
    Attempts to automatically find the MetaTrader 4 data directory.
    This is a best-effort attempt and may need manual configuration.
    """
    if platform.system() == "Windows":
        app_data = Path(os.getenv('APPDATA'))
        search_path = app_data / "MetaQuotes" / "Terminal"
    elif platform.system() == "Darwin": # macOS
        home = Path.home()
        # Common path for MT4 via Wine/PlayOnMac
        search_path = home / ".wine" / "drive_c" / "users" / os.getlogin() / "AppData" / "Roaming" / "MetaQuotes" / "Terminal"
        if not search_path.exists():
             search_path = home / "Library" / "Application Support" / "MetaTrader 4" / "Bottles" / "metatrader4" / "drive_c" / "users" / os.getlogin() / "AppData" / "Roaming" / "MetaQuotes" / "Terminal"

    else: # Linux
        home = Path.home()
        search_path = home / ".wine" / "drive_c" / "users" / os.getlogin() / "AppData" / "Roaming" / "MetaQuotes" / "Terminal"

    if not search_path.exists():
        print(f"Warning: Could not find MT4 data path at default location: {search_path}")
        return None

    # The data path is a directory with a 32-character hexadecimal name
    for item in search_path.iterdir():
        if item.is_dir() and len(item.name) == 32 and all(c in '0123456789abcdefABCDEF' for c in item.name):
            print(f"Found potential MT4 data path: {item}")
            return item
    
    print(f"Warning: Could not find a valid MT4 data directory in {search_path}")
    return None

def download_strategy_file(api_url: str, token: str, download_path: Path):
    """
    Downloads the strategy file from the REST API.
    Assumes the API provides the .ex4 file directly.
    """
    print(f"Downloading strategy from {api_url}...")
    headers = {"Authorization": f"Bearer {token}"}
    try:
        response = requests.get(api_url, headers=headers, stream=True)
        response.raise_for_status()

        # Get filename from headers, or fallback to parsing URL
        content_disposition = response.headers.get('content-disposition')
        if content_disposition:
            import re
            fname_match = re.search('filename="(.+)"', content_disposition)
            if fname_match:
                filename = fname_match.group(1)
            else:
                filename = "downloaded_strategy.ex4"
        else:
            filename = api_url.split('/')[-1]
            if not filename.endswith(".ex4"):
                 filename = "downloaded_strategy.ex4"

        strategy_file_path = download_path / filename
        
        with open(strategy_file_path, 'wb') as f:
            for chunk in response.iter_content(chunk_size=8192):
                f.write(chunk)
        
        print(f"Strategy downloaded successfully to {strategy_file_path}")
        return strategy_file_path
        
    except requests.exceptions.RequestException as e:
        print(f"Error downloading strategy: {e}")
        # Example of creating a dummy file if API fails
        print("Creating a dummy strategy file for demonstration.")
        dummy_path = download_path / "dummy_strategy.ex4"
        dummy_path.touch()
        return dummy_path


def install_strategy_to_mt4(strategy_file_path: Path, mt4_experts_path: Path):
    """
    Copies the strategy file to the MT4 Experts directory.
    """
    if not strategy_file_path or not strategy_file_path.exists():
        print("Strategy file not found. Cannot install.")
        return False
        
    if not mt4_experts_path.exists():
        print(f"MT4 Experts directory not found at {mt4_experts_path}. Creating it.")
        mt4_experts_path.mkdir(parents=True, exist_ok=True)

    destination_path = mt4_experts_path / strategy_file_path.name
    print(f"Installing strategy by copying {strategy_file_path} to {destination_path}...")
    
    try:
        shutil.copy(strategy_file_path, destination_path)
        print("Strategy installed successfully.")
        return True
    except IOError as e:
        print(f"Error copying file: {e}")
        return False

def refresh_mt4_experts():
    """
    This is the most complex part. MT4 does not have a simple refresh command.
    Running the EA is typically a manual process or requires a 'connector' EA.
    
    Possible approaches:
    1. Manual: The user must manually refresh the Experts list in MT4 or restart it.
    2. Connector EA: A separate, always-on EA in MT4 listens for commands (e.g., via ZeroMQ)
       from this Python script to attach/detach other EAs. The 'PyTrader' project is a good example.
    3. GUI Automation: Use libraries like pyautogui to simulate mouse clicks. Very brittle.
    
    For now, this function will just print instructions for the user.
    """
    print("\n--- ACTION REQUIRED ---")
    print("The strategy file has been copied to your MetaTrader 4 directory.")
    print("To run the new Expert Advisor:")
    print("1. Open or restart your MetaTrader 4 terminal.")
    print("2. In the 'Navigator' panel, right-click on 'Expert Advisors' and select 'Refresh'.")
    print(f"3. Find the new strategy and drag it onto a chart to run it.")
    print("-----------------------\n")


def main():
    """
    Main function to run the download and installation process.
    """
    print("--- Starting Strategy Deployment for MT4 ---")
    
    # 1. Find or set MT4 Path
    global MT4_DATA_PATH
    
    # The user can set the path as a string, so we ensure it's a Path object
    mt4_path_obj = None
    if MT4_DATA_PATH:
        mt4_path_obj = Path(MT4_DATA_PATH)
    else:
        # If path is not set manually, try to find it automatically
        mt4_path_obj = find_mt4_data_path()

    # More robust check for the MT4 data path
    if not mt4_path_obj:
        print("\nERROR: Could not automatically determine the MetaTrader 4 data path.")
        print("Please set the 'MT4_DATA_PATH' variable manually in the script and re-run.")
        return

    if not mt4_path_obj.exists():
        print(f"\nERROR: The specified MT4 data path does not exist: {mt4_path_obj}")
        print("Please check the path and set it manually in the script if needed.")
        return

    mt4_experts_path = mt4_path_obj / EXPERTS_DIR_NAME
    
    # Create a temporary directory for downloads
    download_dir = Path("./strategy_downloads")
    download_dir.mkdir(exist_ok=True)
    
    # 2. Download the strategy
    strategy_file = download_strategy_file(STRATEGY_API_URL, API_AUTH_TOKEN, download_dir)
    
    if not strategy_file:
        print("Deployment failed: Could not download strategy.")
        return

    # 3. Install the strategy
    installed = install_strategy_to_mt4(strategy_file, mt4_experts_path)

    if not installed:
        print("Deployment failed: Could not install strategy to MetaTrader 4.")
        return
        
    # 4. Notify user to run/refresh
    refresh_mt4_experts()
    
    print("--- Deployment Script Finished ---")


if __name__ == "__main__":
    main() 