﻿var CONFIG = {
 "data": {
  "_lastModified": 1555048465910,
  "hasNameColumn": false,
  "lastModified": 1555048465910,
  "nameColumnPosition": 0,
  "noCache": true,
  "path": "./data/hot_new.csv",
  "reader": "ext-csv",
  "sheet": "",
  "timeInColumns": false,
  "ddfPath": "./data/hot_new.csv"
 },
 "locale": {
  "filePath": "assets/translation/",
  "id": "en"
 },
 "state": {
  "entities": {
   "autoconfig": {
    "excludeIDs": [
     "tag"
    ],
    "type": "entity_domain"
   },
   "dim": "標的名稱",
   "filter": {},
   "show": {},
   "showFallback": {},
   "showItemsMaxCount": null,
   "skipFilter": false
  },
  "entities_colorlegend": {
   "autoconfig": {
    "excludeIDs": [
     "tag"
    ],
    "type": "entity_domain"
   },
   "dim": "標的名稱",
   "filter": {},
   "show": {},
   "showFallback": {},
   "showItemsMaxCount": null,
   "skipFilter": false
  },
  "marker": {
   "allowSelectMultiple": true,
   "axis_x": {
    "allow": {
     "scales": [
      "linear",
      "log"
     ]
    },
    "autoconfig": {
     "type": "measure"
    },
    "data": "data",
    "domainMax": null,
    "domainMin": null,
    "fixBaseline": null,
    "scaleType": "linear",
    "spaceRef": null,
    "use": "indicator",
    "which": "市佔率",
    "zoomedMax": null,
    "zoomedMin": null
   },
   "color": {
    "allow": {
     "scales": [
      "linear",
      "log",
      "genericLog",
      "time",
      "pow",
      "ordinal"
     ]
    },
    "autoconfig": {},
    "data": "data",
    "palette": {
     "0": "#4cd843",
     "1": "#e83739",
     "2": "#ff7f00",
     "3": "#c027d4",
     "4": "#d66425",
     "5": "#0ab8d8",
     "6": "#bcfa83",
     "7": "#ff8684",
     "8": "#ffb04b",
     "9": "#f599f5",
     "10": "#f4f459",
     "11": "#7fb5ed",
     "_default": "#ffb600"
    },
    "paletteLabels": null,
    "scaleType": "ordinal",
    "spaceRef": "entities",
    "syncModels": [
     "marker_colorlegend"
    ],
    "use": "property",
    "which": "標的名稱"
   },
   "highlight": [],
   "label": {
    "autoconfig": {
     "includeOnlyIDs": [
      "name"
     ],
     "type": "string"
    },
    "data": "data",
    "scaleType": "ordinal",
    "use": "property",
    "which": "標的名稱"
   },
   "limit": 1000,
   "opacityHighlightDim": 0.1,
   "opacityRegular": 1,
   "opacitySelectDim": 0.3,
   "select": [],
   "space": [
    "entities",
    "time"
   ],
   "superHighlight": []
  },
  "marker_colorlegend": {
   "allowSelectMultiple": true,
   "highlight": [],
   "hook_geoshape": {
    "data": "data",
    "use": "property",
    "which": "shape_lores_svg"
   },
   "hook_rank": {
    "data": "data",
    "use": "property",
    "which": "rank"
   },
   "label": {
    "data": "data",
    "use": "property",
    "which": "name"
   },
   "limit": 1000,
   "opacityHighlightDim": 0.1,
   "opacityRegular": 1,
   "opacitySelectDim": 0.3,
   "select": [],
   "space": [
    "entities_colorlegend"
   ],
   "superHighlight": []
  },
  "time": {
   "autoconfig": {
    "type": "time"
   },
   "delay": 150,
   "delayThresholdX2": 90,
   "delayThresholdX4": 45,
   "dim": "時間",
   "end": "2019-02",
   "endOrigin": null,
   "endSelected": "2019-02",
   "format": {
    "data": null,
    "ui": null
   },
   "immediatePlay": true,
   "loop": false,
   "offset": 0,
   "playable": true,
   "playing": false,
   "record": false,
   "round": "round",
   "start": "2015-06",
   "startOrigin": null,
   "startSelected": "2015-06",
   "step": 1,
   "unit": "month",
   "value": "2019-02"
  }
 },
 "ui": {
  "buttons": [
   "colors",
   "find",
   "moreoptions",
   "fullscreen",
   "presentation"
  ],
  "chart": {},
  "datawarning": {
   "doubtDomain": [],
   "doubtRange": []
  },
  "dialogs": {
   "dialog": {
    "find": {
     "enableSelectShowSwitch": false
    }
   },
   "moreoptions": [
    "opacity",
    "speed",
    "colors",
    "presentation",
    "about"
   ],
   "popup": [
    "timedisplay",
    "colors",
    "find",
    "moreoptions"
   ],
   "sidebar": [
    "timedisplay",
    "colors",
    "find"
   ]
  },
  "presentation": false,
  "splash": false
 },
 "chartType": "BarRankChart"
};