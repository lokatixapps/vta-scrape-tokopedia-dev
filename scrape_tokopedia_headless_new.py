import json
from flask import Flask, request

from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.common.exceptions import NoSuchElementException
import time

app = Flask(__name__)

def convert_price(price_str):
    price_str = price_str.replace("Rp", "").replace(".", "")
    if "jt" in price_str:
        price_str = price_str.replace("jt", "").replace(",", ".")
        price = float(price_str) * 1000000
    else:
        price = float(price_str.replace(",", ""))
    return int(price)
    
def scrape_content(product):
    results = []
    try:
        options = webdriver.ChromeOptions()
        options.add_experimental_option("excludeSwitches",["ignore-certificate-errors"])
        options.add_argument('--headless=new')
        options.add_argument('--no-sandbox')
        options.add_argument('--disable-dev-shm-usage')
        options.add_argument('user-agent=Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36')
        driver = webdriver.Chrome(options=options)
        driver.implicitly_wait(10)
        driver.get('https://www.tokopedia.com/search?navsource=home&q=' + product)
        
        containers = driver.find_elements(By.CLASS_NAME, "prd_container-card")
        
        if not containers:
            print("Tidak dapat menemukan konten.")
            return None
        
        for container in containers:
            container_info = {}

            title_element = container.find_element(By.CLASS_NAME, "prd_link-product-name")
            container_info["title"] = title_element.text
            
            price_element = container.find_element(By.CLASS_NAME, "prd_link-product-price")
            price = convert_price(price_element.text)
            container_info["price"] = price

            try:
                location_element = container.find_element(By.CLASS_NAME, "prd_link-shop-loc")
                container_info["location"] = location_element.text
            except NoSuchElementException:
                container_info["location"] = "NULL"

            url_element = container.find_element(By.TAG_NAME, "a")
            container_info["url"] = url_element.get_attribute("href")
            
            results.append(container_info)
        
        return results
    
    except NoSuchElementException as e:
        print("Tidak dapat menemukan elemen:", e)
        return None
    
    except Exception as e:
        print("Terjadi kesalahan:", e)
        return None
    
    finally:
        if 'driver' in locals():
            driver.quit()

@app.route('/scrape', methods=['GET'])
def handle_scrape():
    product = request.args.get('product')
    if not product:
        return json.dumps({"error": "Parameter URL diperlukan."}), 400
    
    start_time = time.time()
    
    detail_contents = scrape_content(product)
    
    end_time = time.time()
    elapsed_time = end_time - start_time
    
    if detail_contents:
        result = {"data": detail_contents, "elapsed_time": elapsed_time}
        return json.dumps(result), 200
    else:
        return json.dumps({"error": "Gagal mendapatkan konten."}), 500

if __name__ == '__main__':
    app.run(debug=True)

# set FLASK_APP=scrape_tokopedia_headless_new.py
# python -m flask run