import{_ as l}from"./u-icon.D5CTgztk.js";import{V as t,x as e,y as a,z as s,A as i,C as u,H as d,Y as n,Z as f,I as c,L as o,M as _,D as r,E as m,F as p,O as h,J as g,cv as k}from"./index-BnwyELQR.js";import{r as v}from"./uni-app.es.dFrsx9lz.js";import{_ as w}from"./u-tabs.pxpLvgTd.js";import{_ as x}from"./u-button._TsYjNnj.js";import{_ as b}from"./u-input.C8_hRWIx.js";import{d as C}from"./pz.DHi6PY10.js";import{_ as j}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./u-loading-icon.DDFfdENh.js";const z=j({__name:"user-center-trading-account-operation",props:{name:String,code:String},setup(j){const z=j,V=t(0),y=[{name:"买入"},{name:"卖出"},{name:"撤单"},{name:"持仓"},{name:"查询"}],D=[{name:"委托记录"},{name:"成交记录"},{name:"交割查询"}];console.log(z.code),C({code:z.code,t:parseInt(Date.now()/1e3)});const S=l=>{V.value=l.index};return(t,C)=>{const j=v(e("up-icon"),l),z=h,F=u,I=v(e("up-tabs"),w),O=v(e("up-button"),x),U=v(e("up-input"),b);return a(),s(F,null,{default:i((()=>[d(F,{class:"custom-nav-bar-box"},{default:i((()=>[d(F,{class:"custom-nav-bar"},{default:i((()=>[d(j,{name:"arrow-left",size:"18",onClick:n(f)},null,8,["onClick"]),d(F,{class:"nav-bar-center"},{default:i((()=>[d(z,{class:"title"},{default:i((()=>[c("交易账号")])),_:1}),d(F,{class:"stock-select"},{default:i((()=>[d(z,{class:"stock-code"},{default:i((()=>[c("60756192")])),_:1}),d(j,{name:"arrow-down",size:"12",color:"#ffae90"})])),_:1})])),_:1})])),_:1})])),_:1}),d(F,{class:"top-placeholder"}),d(I,{list:y,onClick:S,current:V.value,lineColor:"#FD4630",lineWidth:"30",scrollable:!1,inactiveStyle:{color:"#979797","font-size":"27rpx","font-weight":"400"},activeStyle:{color:"#FD4630","font-weight":"500","font-size":"31rpx"}},null,8,["current"]),d(F,{class:"page-main"},{default:i((()=>[o(d(F,{class:"transaction-content"},{default:i((()=>[d(F,{class:"top-content"},{default:i((()=>[d(F,{class:"top-content-header"},{default:i((()=>[d(F,{class:"top-content-header-left"},{default:i((()=>[d(F,{class:"stock-name"},{default:i((()=>[d(z,null,{default:i((()=>[c("中国石化")])),_:1}),d(z,null,{default:i((()=>[c("600028")])),_:1})])),_:1}),d(z,{class:"stock-price up-color"},{default:i((()=>[c("6.41")])),_:1}),d(z,{class:"stock-range down-color"},{default:i((()=>[c("-0.01")])),_:1}),d(z,{class:"stock-rate down-color"},{default:i((()=>[c("-0.31%")])),_:1})])),_:1}),d(O,{icon:"search",plain:"",color:"#ff4a07",text:"搜索股票"})])),_:1}),d(F,{class:"top-content-bottom"},{default:i((()=>[d(F,{class:"data-content"},{default:i((()=>[d(z,null,{default:i((()=>[c("最高")])),_:1}),d(F,null,{default:i((()=>[c("6.46")])),_:1})])),_:1}),d(F,{class:"data-content"},{default:i((()=>[d(z,null,{default:i((()=>[c("最低")])),_:1}),d(F,null,{default:i((()=>[c("6.46")])),_:1})])),_:1}),d(F,{class:"data-content"},{default:i((()=>[d(z,null,{default:i((()=>[c("今开")])),_:1}),d(F,null,{default:i((()=>[c("6.46")])),_:1})])),_:1}),d(F,{class:"data-content"},{default:i((()=>[d(z,null,{default:i((()=>[c("昨收")])),_:1}),d(F,null,{default:i((()=>[c("6.46")])),_:1})])),_:1})])),_:1})])),_:1}),d(F,{class:"disk-file"},{default:i((()=>[d(F,{class:"disk-file-item"},{default:i((()=>[d(F,{class:"disk-file-item-header"},{default:i((()=>[c("买盘档")])),_:1}),d(F,{class:"disk-file-item-list"},{default:i((()=>[d(F,{class:"disk-file-item-list-item"},{default:i((()=>[d(z,{class:"list-item-title"},{default:i((()=>[c("买一")])),_:1}),d(F,{class:"list-item-right"},{default:i((()=>[d(z,{class:"down-color"},{default:i((()=>[c("6.39")])),_:1}),d(z,null,{default:i((()=>[c("536000.00")])),_:1})])),_:1})])),_:1}),d(F,{class:"disk-file-item-list-item"},{default:i((()=>[d(z,{class:"list-item-title"},{default:i((()=>[c("买二")])),_:1}),d(F,{class:"list-item-right"},{default:i((()=>[d(z,{class:"down-color"},{default:i((()=>[c("6.39")])),_:1}),d(z,null,{default:i((()=>[c("536000.00")])),_:1})])),_:1})])),_:1}),d(F,{class:"disk-file-item-list-item"},{default:i((()=>[d(z,{class:"list-item-title"},{default:i((()=>[c("买三")])),_:1}),d(F,{class:"list-item-right"},{default:i((()=>[d(z,{class:"down-color"},{default:i((()=>[c("6.39")])),_:1}),d(z,null,{default:i((()=>[c("536000.00")])),_:1})])),_:1})])),_:1}),d(F,{class:"disk-file-item-list-item"},{default:i((()=>[d(z,{class:"list-item-title"},{default:i((()=>[c("买四")])),_:1}),d(F,{class:"list-item-right"},{default:i((()=>[d(z,{class:"down-color"},{default:i((()=>[c("6.39")])),_:1}),d(z,null,{default:i((()=>[c("536000.00")])),_:1})])),_:1})])),_:1}),d(F,{class:"disk-file-item-list-item"},{default:i((()=>[d(z,{class:"list-item-title"},{default:i((()=>[c("买五")])),_:1}),d(F,{class:"list-item-right"},{default:i((()=>[d(z,{class:"down-color"},{default:i((()=>[c("6.39")])),_:1}),d(z,null,{default:i((()=>[c("536000.00")])),_:1})])),_:1})])),_:1})])),_:1})])),_:1}),d(F,{class:"disk-file-item"},{default:i((()=>[d(F,{class:"disk-file-item-header"},{default:i((()=>[c("卖盘档")])),_:1}),d(F,{class:"disk-file-item-list"},{default:i((()=>[d(F,{class:"disk-file-item-list-item"},{default:i((()=>[d(z,{class:"list-item-title"},{default:i((()=>[c("卖一")])),_:1}),d(F,{class:"list-item-right"},{default:i((()=>[d(z,{class:"down-color"},{default:i((()=>[c("6.39")])),_:1}),d(z,null,{default:i((()=>[c("536000.00")])),_:1})])),_:1})])),_:1}),d(F,{class:"disk-file-item-list-item"},{default:i((()=>[d(z,{class:"list-item-title"},{default:i((()=>[c("卖二")])),_:1}),d(F,{class:"list-item-right"},{default:i((()=>[d(z,{class:"down-color"},{default:i((()=>[c("6.39")])),_:1}),d(z,null,{default:i((()=>[c("536000.00")])),_:1})])),_:1})])),_:1}),d(F,{class:"disk-file-item-list-item"},{default:i((()=>[d(z,{class:"list-item-title"},{default:i((()=>[c("卖三")])),_:1}),d(F,{class:"list-item-right"},{default:i((()=>[d(z,{class:"down-color"},{default:i((()=>[c("6.39")])),_:1}),d(z,null,{default:i((()=>[c("536000.00")])),_:1})])),_:1})])),_:1}),d(F,{class:"disk-file-item-list-item"},{default:i((()=>[d(z,{class:"list-item-title"},{default:i((()=>[c("卖四")])),_:1}),d(F,{class:"list-item-right"},{default:i((()=>[d(z,{class:"down-color"},{default:i((()=>[c("6.39")])),_:1}),d(z,null,{default:i((()=>[c("536000.00")])),_:1})])),_:1})])),_:1}),d(F,{class:"disk-file-item-list-item"},{default:i((()=>[d(z,{class:"list-item-title"},{default:i((()=>[c("卖五")])),_:1}),d(F,{class:"list-item-right"},{default:i((()=>[d(z,{class:"down-color"},{default:i((()=>[c("6.39")])),_:1}),d(z,null,{default:i((()=>[c("536000.00")])),_:1})])),_:1})])),_:1})])),_:1})])),_:1})])),_:1}),d(F,{class:"stock-operation"},{default:i((()=>[d(F,{class:"operation-item"},{default:i((()=>[d(F,{class:"operation-item-left"},{default:i((()=>[d(z,null,{default:i((()=>[c("市价")])),_:1}),d(j,{name:"play-right-fill",size:"12",color:"#c2c2c2"})])),_:1}),d(F,{class:"operation-item-right"},{default:i((()=>[d(O,{text:"+",color:"#ff4500"}),d(U,{border:"surround",modelValue:t.value,"onUpdate:modelValue":C[0]||(C[0]=l=>t.value=l),onChange:t.change},null,8,["modelValue","onChange"]),d(O,{text:"-",color:"#05aa3b"})])),_:1})])),_:1}),d(F,{class:"operation-item"},{default:i((()=>[d(F,{class:"operation-item-left"},{default:i((()=>[d(z,null,{default:i((()=>[c("数量")])),_:1})])),_:1}),d(F,{class:"operation-item-right"},{default:i((()=>[d(O,{text:"+",color:"#ff4500"}),d(U,{placeholder:"账号可购买1股",border:"surround",modelValue:t.value,"onUpdate:modelValue":C[1]||(C[1]=l=>t.value=l),onChange:t.change},null,8,["modelValue","onChange"]),d(O,{text:"-",color:"#05aa3b"})])),_:1})])),_:1}),d(F,{class:"operation-select"},{default:i((()=>[d(F,{class:"select-item select-item-active"},{default:i((()=>[c(" 全仓 ")])),_:1}),d(F,{class:"select-item"},{default:i((()=>[c(" 1 / 2 ")])),_:1}),d(F,{class:"select-item"},{default:i((()=>[c(" 1 / 3 ")])),_:1}),d(F,{class:"select-item"},{default:i((()=>[c(" 1 / 4 ")])),_:1})])),_:1}),d(F,{class:"funding-info"},{default:i((()=>[d(F,{class:"funding-info-item"},{default:i((()=>[d(z,null,{default:i((()=>[c("委托金额：>")])),_:1}),d(z,null,{default:i((()=>[c("648")])),_:1})])),_:1}),d(F,{class:"funding-info-item"},{default:i((()=>[d(z,null,{default:i((()=>[c("可用资金：>")])),_:1}),d(z,null,{default:i((()=>[c("648")])),_:1})])),_:1})])),_:1}),d(F,{class:"trade-button"},{default:i((()=>[d(O,{text:0===V.value?"买入":"卖出",color:"#ff4500"},null,8,["text"])])),_:1})])),_:1}),d(F,{class:"take-position"},{default:i((()=>[d(F,{class:"take-position-header"},{default:i((()=>[d(F,null,{default:i((()=>[c("持仓>")])),_:1}),d(j,{name:"reload",size:"20",color:"#252525"})])),_:1}),d(F,{class:"list-content"},{default:i((()=>[d(F,{class:"list-header"},{default:i((()=>[d(F,{class:"list-header-inner"},{default:i((()=>[d(F,{class:"list-header-item"},{default:i((()=>[c("名称")])),_:1}),d(F,{class:"list-header-item"},{default:i((()=>[c("可用/持仓")])),_:1}),d(F,{class:"list-header-item"},{default:i((()=>[c("现价/成本")])),_:1}),d(F,{class:"list-header-item"},{default:i((()=>[c("盈亏/市值")])),_:1})])),_:1})])),_:1}),d(F,{class:"list-main-content"},{default:i((()=>[d(F,{class:"list-item"},{default:i((()=>[d(F,{class:"list-item-inner"},{default:i((()=>[d(z,null,{default:i((()=>[c("中国石化")])),_:1}),d(z,null,{default:i((()=>[c("6.44")])),_:1})])),_:1}),d(F,{class:"list-item-inner"},{default:i((()=>[d(z,null,{default:i((()=>[c("100.00")])),_:1}),d(z,null,{default:i((()=>[c("6.44")])),_:1})])),_:1}),d(F,{class:"list-item-inner"},{default:i((()=>[d(z,null,{default:i((()=>[c("2024-07-10")])),_:1}),d(z,null,{default:i((()=>[c("14:15:48")])),_:1})])),_:1}),d(F,{class:"list-item-inner"},{default:i((()=>[d(z,null,{default:i((()=>[c("买入委托")])),_:1}),d(z,null,{default:i((()=>[c("已成")])),_:1})])),_:1})])),_:1})])),_:1})])),_:1})])),_:1})])),_:1},512),[[_,0===V.value||1===V.value]]),o(d(F,null,{default:i((()=>[d(F,{class:"list-content"},{default:i((()=>[d(F,{class:"list-header"},{default:i((()=>[d(F,{class:"list-header-inner"},{default:i((()=>[d(F,{class:"list-header-item"},{default:i((()=>[c("名称/代码")])),_:1}),d(F,{class:"list-header-item"},{default:i((()=>[c("委托价/委托量")])),_:1}),d(F,{class:"list-header-item"},{default:i((()=>[c("状态/时间")])),_:1}),d(F,{class:"list-header-item"},{default:i((()=>[c("方向/操作")])),_:1})])),_:1})])),_:1}),d(F,{class:"list-main-content"},{default:i((()=>[d(F,{class:"list-item"},{default:i((()=>[d(F,{class:"list-item-inner"},{default:i((()=>[d(z,null,{default:i((()=>[c("中国石化")])),_:1}),d(z,null,{default:i((()=>[c("6.44")])),_:1})])),_:1}),d(F,{class:"list-item-inner"},{default:i((()=>[d(z,null,{default:i((()=>[c("100.00")])),_:1}),d(z,null,{default:i((()=>[c("6.44")])),_:1})])),_:1}),d(F,{class:"list-item-inner"},{default:i((()=>[d(z,null,{default:i((()=>[c("2024-07-10")])),_:1}),d(z,null,{default:i((()=>[c("14:15:48")])),_:1})])),_:1}),d(F,{class:"list-item-inner"},{default:i((()=>[d(z,null,{default:i((()=>[c("买入委托")])),_:1}),d(z,null,{default:i((()=>[c("已成")])),_:1})])),_:1})])),_:1})])),_:1})])),_:1})])),_:1},512),[[_,2===V.value]]),o(d(F,null,{default:i((()=>[d(F,{class:"list-content"},{default:i((()=>[d(F,{class:"list-header"},{default:i((()=>[d(F,{class:"list-header-inner"},{default:i((()=>[d(F,{class:"list-header-item"},{default:i((()=>[c("名称/代码")])),_:1}),d(F,{class:"list-header-item"},{default:i((()=>[c("可用/持仓")])),_:1}),d(F,{class:"list-header-item"},{default:i((()=>[c("现价/成本")])),_:1}),d(F,{class:"list-header-item"},{default:i((()=>[c("盈亏/市值")])),_:1})])),_:1})])),_:1}),d(F,{class:"list-main-content"},{default:i((()=>[d(F,{class:"list-item"},{default:i((()=>[d(F,{class:"list-item-inner"},{default:i((()=>[d(z,null,{default:i((()=>[c("中国石化")])),_:1}),d(z,null,{default:i((()=>[c("6.44")])),_:1})])),_:1}),d(F,{class:"list-item-inner"},{default:i((()=>[d(z,null,{default:i((()=>[c("100.00")])),_:1}),d(z,null,{default:i((()=>[c("6.44")])),_:1})])),_:1}),d(F,{class:"list-item-inner"},{default:i((()=>[d(z,null,{default:i((()=>[c("2024-07-10")])),_:1}),d(z,null,{default:i((()=>[c("14:15:48")])),_:1})])),_:1}),d(F,{class:"list-item-inner"},{default:i((()=>[d(z,null,{default:i((()=>[c("买入委托")])),_:1}),d(z,null,{default:i((()=>[c("已成")])),_:1})])),_:1})])),_:1})])),_:1})])),_:1})])),_:1},512),[[_,3===V.value]]),o(d(F,{class:"search-list"},{default:i((()=>[(a(),r(p,null,m(D,((l,t)=>d(F,{class:"list-item",key:l.name,onClick:l=>{0===t&&k()}},{default:i((()=>[d(z,null,{default:i((()=>[c(g(l.name),1)])),_:2},1024),d(j,{name:"arrow-right",size:"18",color:"#c7c7cc"})])),_:2},1032,["onClick"]))),64))])),_:1},512),[[_,4===V.value]])])),_:1})])),_:1})}}},[["__scopeId","data-v-820688fd"]]);export{z as default};
