# Data Crawler&Cleaning&Visualization
大學專題-過投信投資組合之動態分析進行投資決策之研究

## 實作目的
投資理財已經普遍成為現代人的日常活動之一，而穩健型投資人通常會以基金持股作為主要投資籌碼。我們用中華民國證券投資同業公會(以下簡稱SITCA)所公布的基金國內投資前十大的資料來做分析。
但SITCA所公佈的基金投資組合明細大多是流水帳式的記錄模式，投資人難以清楚掌握投信公司的短期，因此我們想針對收集到的資料進行整理並製作成動態圖表。

## 設計理念與技術
主要分為資料蒐集、資料清理以及資料呈現<br>
資料蒐集：使用Python的Selenium和BeautifulSoup套件進行爬蟲然後再存進資料庫。<br>
資料清理：透過SQL預存程序對爬蟲資料進行清理以及格式化，並另外計算分析相對市占率和成長率等資料。<br>
資料呈現：透過WEB來呈現，後端使用PHP配合前端jQuery的Ajax來達到SPA查詢；使用Gapminder Tools工具來進行動態圖表的繪製，並另外使用Highcharts.js套件來呈現靜態資料視覺化<br>
- Python-Selenium
- Python-BeautifulSoup
- SQL Server(MSSQL)
- HTML+CSS
- PHP 7.4
- jQuery
- Highcharts.js
- Gapminder Tools

## 主要實作功能
著重於資料的整理、計算及視覺化，結合BCG矩陣的概念製作出基金矩陣圖，整合出不同層面的分析資訊使資料更具可靠性，也讓投資人易於進行分析。
- 個股&多股圖表查詢
- 動態圖表查詢
- BCG矩陣圖分析圖
- 近期資料分析圖
- 熱門標的分析圖

### 系統功能
![image](https://github.com/0524088/DataCrawler-Cleaning-Visualization/assets/43835584/b62fc590-8690-44cf-bcad-501f1fef41af)

## 專案影片DEMO展示
https://www.youtube.com/watch?v=jxuEEPHJDVc&ab_channel=DLD

## 製作過程與困難點
由於SITCA的網站資料並不是單純用GET網址的方式去呈現而且有非常多頁的資料要爬，因此一開始沒辦法直接單純用BeautifulSoup套件去抓取資料，後面才發現有Selenium這個套件可以模擬人去操作瀏覽器。

## 後記
這是大學和一個組員合作的專題，很多地方放到現在來看有更好的寫法，像是爬蟲的部分其實也可以不用Selenium，直接借助瀏覽器的開發者工具觀察發出的請求網址，再將其用迴圈的方式去重複爬取；個股&多股查詢、Gapminder的動態圖分別可以整合成一頁即可，透過JS去做切換，在使用者體驗上會比較好。比較好笑的是由於當時並不知道模板引擎，還特地用了兩個iframe一個放navbar一個放contents來達到這個效果。
