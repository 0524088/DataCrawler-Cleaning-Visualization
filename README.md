# Data Crawler&Cleaning&Visualization
大學專題-透過投信投資組合之動態分析進行投資決策之研究

## 目的
以中華民國證券投資同業公會(SITCA)所公布的基金國內投資前十大的資料來做分析，將其整理運算後並以圖表的方式來呈現，使基金投資人能更易的進行分析，並增加資料可讀性。

## 流程
主要分為資料蒐集、資料清理以及資料呈現<br>
**資料蒐集**：使用Python的Selenium和BeautifulSoup套件進行爬蟲然後再存進資料庫。<br>
**資料清理**：透過SQL預存程序對爬蟲資料進行清理以及格式化，並另外計算分析相對市占率和成長率等資料。<br>
**資料呈現**：透過WEB來呈現，後端使用PHP配合前端jQuery的Ajax來達到SPA查詢；使用Gapminder Tools工具來進行動態圖表的繪製，並另外使用Highcharts.js套件來呈現靜態資料視覺化

## 使用工具/技術
- Python-Selenium
- Python-BeautifulSoup
- MSSQL
- HTML & CSS
- PHP 7.4
- Java Script & jQuery
- Highcharts.js
- Gapminder Tools

## 系統功能
![image](https://github.com/0524088/DataCrawler-Cleaning-Visualization/assets/43835584/b62fc590-8690-44cf-bcad-501f1fef41af)
主要著重於資料的整理、計算及視覺化，結合BCG矩陣的概念製作出基金矩陣圖，整合出不同層面的分析資訊使資料更具可靠性，也讓投資人易於進行分析。
系統主要提供：
- 個股&多股圖表查詢
- 動態圖表查詢
- BCG矩陣圖分析圖
- 近期資料分析圖
- 熱門標的分析圖

## DEMO
https://www.youtube.com/watch?v=jxuEEPHJDVc&ab_channel=DLD
