import{_ as l}from"./u-icon.Cinv0AqG.js";import{V as t,x as e,y as a,z as s,B as i,C as u,H as d,Y as n,Z as f,I as c,L as o,M as _,D as r,E as m,F as p,O as h,J as g,cA as k}from"./index-eZLOZRr1.js";import{r as v}from"./uni-app.es.Cg_AhWrk.js";import{_ as w}from"./u-tabs.BPe-IWel.js";import{_ as b}from"./u-button.CF58NdxG.js";import{_ as x}from"./u-input.Bb_I8M-Y.js";import{d as C}from"./pz.CZtIizLj.js";import{_ as j}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./u-loading-icon.C9PbYIxd.js";const z=j({__name:"user-center-trading-account-operation",props:{name:String,code:String},setup(j){const z=j,V=t(0),y=[{name:"买入"},{name:"卖出"},{name:"撤单"},{name:"持仓"},{name:"查询"}],D=[{name:"委托记录"},{name:"成交记录"},{name:"交割查询"}];console.log(z.code),C({code:z.code,t:parseInt(Date.now()/1e3)});const S=l=>{V.value=l.index};return(t,C)=>{const j=v(e("up-icon"),l),z=h,F=i,I=v(e("up-tabs"),w),B=v(e("up-button"),b),E=v(e("up-input"),x);return u(),a(F,null,{default:s((()=>[d(F,{class:"custom-nav-bar-box"},{default:s((()=>[d(F,{class:"custom-nav-bar"},{default:s((()=>[d(j,{name:"arrow-left",size:"18",onClick:n(f)},null,8,["onClick"]),d(F,{class:"nav-bar-center"},{default:s((()=>[d(z,{class:"title"},{default:s((()=>[c("交易账号")])),_:1}),d(F,{class:"stock-select"},{default:s((()=>[d(z,{class:"stock-code"},{default:s((()=>[c("60756192")])),_:1}),d(j,{name:"arrow-down",size:"12",color:"#ffae90"})])),_:1})])),_:1})])),_:1})])),_:1}),d(F,{class:"top-placeholder"}),d(I,{list:y,onClick:S,current:V.value,lineColor:"#FD4630",lineWidth:"30",scrollable:!1,inactiveStyle:{color:"#979797","font-size":"27rpx","font-weight":"400"},activeStyle:{color:"#FD4630","font-weight":"500","font-size":"31rpx"}},null,8,["current"]),d(F,{class:"page-main"},{default:s((()=>[o(d(F,{class:"transaction-content"},{default:s((()=>[d(F,{class:"top-content"},{default:s((()=>[d(F,{class:"top-content-header"},{default:s((()=>[d(F,{class:"top-content-header-left"},{default:s((()=>[d(F,{class:"stock-name"},{default:s((()=>[d(z,null,{default:s((()=>[c("中国石化")])),_:1}),d(z,null,{default:s((()=>[c("600028")])),_:1})])),_:1}),d(z,{class:"stock-price up-color"},{default:s((()=>[c("6.41")])),_:1}),d(z,{class:"stock-range down-color"},{default:s((()=>[c("-0.01")])),_:1}),d(z,{class:"stock-rate down-color"},{default:s((()=>[c("-0.31%")])),_:1})])),_:1}),d(B,{icon:"search",plain:"",color:"#ff4a07",text:"搜索股票"})])),_:1}),d(F,{class:"top-content-bottom"},{default:s((()=>[d(F,{class:"data-content"},{default:s((()=>[d(z,null,{default:s((()=>[c("最高")])),_:1}),d(F,null,{default:s((()=>[c("6.46")])),_:1})])),_:1}),d(F,{class:"data-content"},{default:s((()=>[d(z,null,{default:s((()=>[c("最低")])),_:1}),d(F,null,{default:s((()=>[c("6.46")])),_:1})])),_:1}),d(F,{class:"data-content"},{default:s((()=>[d(z,null,{default:s((()=>[c("今开")])),_:1}),d(F,null,{default:s((()=>[c("6.46")])),_:1})])),_:1}),d(F,{class:"data-content"},{default:s((()=>[d(z,null,{default:s((()=>[c("昨收")])),_:1}),d(F,null,{default:s((()=>[c("6.46")])),_:1})])),_:1})])),_:1})])),_:1}),d(F,{class:"disk-file"},{default:s((()=>[d(F,{class:"disk-file-item"},{default:s((()=>[d(F,{class:"disk-file-item-header"},{default:s((()=>[c("买盘档")])),_:1}),d(F,{class:"disk-file-item-list"},{default:s((()=>[d(F,{class:"disk-file-item-list-item"},{default:s((()=>[d(z,{class:"list-item-title"},{default:s((()=>[c("买一")])),_:1}),d(F,{class:"list-item-right"},{default:s((()=>[d(z,{class:"down-color"},{default:s((()=>[c("6.39")])),_:1}),d(z,null,{default:s((()=>[c("536000.00")])),_:1})])),_:1})])),_:1}),d(F,{class:"disk-file-item-list-item"},{default:s((()=>[d(z,{class:"list-item-title"},{default:s((()=>[c("买二")])),_:1}),d(F,{class:"list-item-right"},{default:s((()=>[d(z,{class:"down-color"},{default:s((()=>[c("6.39")])),_:1}),d(z,null,{default:s((()=>[c("536000.00")])),_:1})])),_:1})])),_:1}),d(F,{class:"disk-file-item-list-item"},{default:s((()=>[d(z,{class:"list-item-title"},{default:s((()=>[c("买三")])),_:1}),d(F,{class:"list-item-right"},{default:s((()=>[d(z,{class:"down-color"},{default:s((()=>[c("6.39")])),_:1}),d(z,null,{default:s((()=>[c("536000.00")])),_:1})])),_:1})])),_:1}),d(F,{class:"disk-file-item-list-item"},{default:s((()=>[d(z,{class:"list-item-title"},{default:s((()=>[c("买四")])),_:1}),d(F,{class:"list-item-right"},{default:s((()=>[d(z,{class:"down-color"},{default:s((()=>[c("6.39")])),_:1}),d(z,null,{default:s((()=>[c("536000.00")])),_:1})])),_:1})])),_:1}),d(F,{class:"disk-file-item-list-item"},{default:s((()=>[d(z,{class:"list-item-title"},{default:s((()=>[c("买五")])),_:1}),d(F,{class:"list-item-right"},{default:s((()=>[d(z,{class:"down-color"},{default:s((()=>[c("6.39")])),_:1}),d(z,null,{default:s((()=>[c("536000.00")])),_:1})])),_:1})])),_:1})])),_:1})])),_:1}),d(F,{class:"disk-file-item"},{default:s((()=>[d(F,{class:"disk-file-item-header"},{default:s((()=>[c("卖盘档")])),_:1}),d(F,{class:"disk-file-item-list"},{default:s((()=>[d(F,{class:"disk-file-item-list-item"},{default:s((()=>[d(z,{class:"list-item-title"},{default:s((()=>[c("卖一")])),_:1}),d(F,{class:"list-item-right"},{default:s((()=>[d(z,{class:"down-color"},{default:s((()=>[c("6.39")])),_:1}),d(z,null,{default:s((()=>[c("536000.00")])),_:1})])),_:1})])),_:1}),d(F,{class:"disk-file-item-list-item"},{default:s((()=>[d(z,{class:"list-item-title"},{default:s((()=>[c("卖二")])),_:1}),d(F,{class:"list-item-right"},{default:s((()=>[d(z,{class:"down-color"},{default:s((()=>[c("6.39")])),_:1}),d(z,null,{default:s((()=>[c("536000.00")])),_:1})])),_:1})])),_:1}),d(F,{class:"disk-file-item-list-item"},{default:s((()=>[d(z,{class:"list-item-title"},{default:s((()=>[c("卖三")])),_:1}),d(F,{class:"list-item-right"},{default:s((()=>[d(z,{class:"down-color"},{default:s((()=>[c("6.39")])),_:1}),d(z,null,{default:s((()=>[c("536000.00")])),_:1})])),_:1})])),_:1}),d(F,{class:"disk-file-item-list-item"},{default:s((()=>[d(z,{class:"list-item-title"},{default:s((()=>[c("卖四")])),_:1}),d(F,{class:"list-item-right"},{default:s((()=>[d(z,{class:"down-color"},{default:s((()=>[c("6.39")])),_:1}),d(z,null,{default:s((()=>[c("536000.00")])),_:1})])),_:1})])),_:1}),d(F,{class:"disk-file-item-list-item"},{default:s((()=>[d(z,{class:"list-item-title"},{default:s((()=>[c("卖五")])),_:1}),d(F,{class:"list-item-right"},{default:s((()=>[d(z,{class:"down-color"},{default:s((()=>[c("6.39")])),_:1}),d(z,null,{default:s((()=>[c("536000.00")])),_:1})])),_:1})])),_:1})])),_:1})])),_:1})])),_:1}),d(F,{class:"stock-operation"},{default:s((()=>[d(F,{class:"operation-item"},{default:s((()=>[d(F,{class:"operation-item-left"},{default:s((()=>[d(z,null,{default:s((()=>[c("市价")])),_:1}),d(j,{name:"play-right-fill",size:"12",color:"#c2c2c2"})])),_:1}),d(F,{class:"operation-item-right"},{default:s((()=>[d(B,{text:"+",color:"#ff4500"}),d(E,{border:"surround",modelValue:t.value,"onUpdate:modelValue":C[0]||(C[0]=l=>t.value=l),onChange:t.change},null,8,["modelValue","onChange"]),d(B,{text:"-",color:"#05aa3b"})])),_:1})])),_:1}),d(F,{class:"operation-item"},{default:s((()=>[d(F,{class:"operation-item-left"},{default:s((()=>[d(z,null,{default:s((()=>[c("数量")])),_:1})])),_:1}),d(F,{class:"operation-item-right"},{default:s((()=>[d(B,{text:"+",color:"#ff4500"}),d(E,{placeholder:"账号可购买1股",border:"surround",modelValue:t.value,"onUpdate:modelValue":C[1]||(C[1]=l=>t.value=l),onChange:t.change},null,8,["modelValue","onChange"]),d(B,{text:"-",color:"#05aa3b"})])),_:1})])),_:1}),d(F,{class:"operation-select"},{default:s((()=>[d(F,{class:"select-item select-item-active"},{default:s((()=>[c(" 全仓 ")])),_:1}),d(F,{class:"select-item"},{default:s((()=>[c(" 1 / 2 ")])),_:1}),d(F,{class:"select-item"},{default:s((()=>[c(" 1 / 3 ")])),_:1}),d(F,{class:"select-item"},{default:s((()=>[c(" 1 / 4 ")])),_:1})])),_:1}),d(F,{class:"funding-info"},{default:s((()=>[d(F,{class:"funding-info-item"},{default:s((()=>[d(z,null,{default:s((()=>[c("委托金额：>")])),_:1}),d(z,null,{default:s((()=>[c("648")])),_:1})])),_:1}),d(F,{class:"funding-info-item"},{default:s((()=>[d(z,null,{default:s((()=>[c("可用资金：>")])),_:1}),d(z,null,{default:s((()=>[c("648")])),_:1})])),_:1})])),_:1}),d(F,{class:"trade-button"},{default:s((()=>[d(B,{text:0===V.value?"买入":"卖出",color:"#ff4500"},null,8,["text"])])),_:1})])),_:1}),d(F,{class:"take-position"},{default:s((()=>[d(F,{class:"take-position-header"},{default:s((()=>[d(F,null,{default:s((()=>[c("持仓>")])),_:1}),d(j,{name:"reload",size:"20",color:"#252525"})])),_:1}),d(F,{class:"list-content"},{default:s((()=>[d(F,{class:"list-header"},{default:s((()=>[d(F,{class:"list-header-inner"},{default:s((()=>[d(F,{class:"list-header-item"},{default:s((()=>[c("名称")])),_:1}),d(F,{class:"list-header-item"},{default:s((()=>[c("可用/持仓")])),_:1}),d(F,{class:"list-header-item"},{default:s((()=>[c("现价/成本")])),_:1}),d(F,{class:"list-header-item"},{default:s((()=>[c("盈亏/市值")])),_:1})])),_:1})])),_:1}),d(F,{class:"list-main-content"},{default:s((()=>[d(F,{class:"list-item"},{default:s((()=>[d(F,{class:"list-item-inner"},{default:s((()=>[d(z,null,{default:s((()=>[c("中国石化")])),_:1}),d(z,null,{default:s((()=>[c("6.44")])),_:1})])),_:1}),d(F,{class:"list-item-inner"},{default:s((()=>[d(z,null,{default:s((()=>[c("100.00")])),_:1}),d(z,null,{default:s((()=>[c("6.44")])),_:1})])),_:1}),d(F,{class:"list-item-inner"},{default:s((()=>[d(z,null,{default:s((()=>[c("2024-07-10")])),_:1}),d(z,null,{default:s((()=>[c("14:15:48")])),_:1})])),_:1}),d(F,{class:"list-item-inner"},{default:s((()=>[d(z,null,{default:s((()=>[c("买入委托")])),_:1}),d(z,null,{default:s((()=>[c("已成")])),_:1})])),_:1})])),_:1})])),_:1})])),_:1})])),_:1})])),_:1},512),[[_,0===V.value||1===V.value]]),o(d(F,null,{default:s((()=>[d(F,{class:"list-content"},{default:s((()=>[d(F,{class:"list-header"},{default:s((()=>[d(F,{class:"list-header-inner"},{default:s((()=>[d(F,{class:"list-header-item"},{default:s((()=>[c("名称/代码")])),_:1}),d(F,{class:"list-header-item"},{default:s((()=>[c("委托价/委托量")])),_:1}),d(F,{class:"list-header-item"},{default:s((()=>[c("状态/时间")])),_:1}),d(F,{class:"list-header-item"},{default:s((()=>[c("方向/操作")])),_:1})])),_:1})])),_:1}),d(F,{class:"list-main-content"},{default:s((()=>[d(F,{class:"list-item"},{default:s((()=>[d(F,{class:"list-item-inner"},{default:s((()=>[d(z,null,{default:s((()=>[c("中国石化")])),_:1}),d(z,null,{default:s((()=>[c("6.44")])),_:1})])),_:1}),d(F,{class:"list-item-inner"},{default:s((()=>[d(z,null,{default:s((()=>[c("100.00")])),_:1}),d(z,null,{default:s((()=>[c("6.44")])),_:1})])),_:1}),d(F,{class:"list-item-inner"},{default:s((()=>[d(z,null,{default:s((()=>[c("2024-07-10")])),_:1}),d(z,null,{default:s((()=>[c("14:15:48")])),_:1})])),_:1}),d(F,{class:"list-item-inner"},{default:s((()=>[d(z,null,{default:s((()=>[c("买入委托")])),_:1}),d(z,null,{default:s((()=>[c("已成")])),_:1})])),_:1})])),_:1})])),_:1})])),_:1})])),_:1},512),[[_,2===V.value]]),o(d(F,null,{default:s((()=>[d(F,{class:"list-content"},{default:s((()=>[d(F,{class:"list-header"},{default:s((()=>[d(F,{class:"list-header-inner"},{default:s((()=>[d(F,{class:"list-header-item"},{default:s((()=>[c("名称/代码")])),_:1}),d(F,{class:"list-header-item"},{default:s((()=>[c("可用/持仓")])),_:1}),d(F,{class:"list-header-item"},{default:s((()=>[c("现价/成本")])),_:1}),d(F,{class:"list-header-item"},{default:s((()=>[c("盈亏/市值")])),_:1})])),_:1})])),_:1}),d(F,{class:"list-main-content"},{default:s((()=>[d(F,{class:"list-item"},{default:s((()=>[d(F,{class:"list-item-inner"},{default:s((()=>[d(z,null,{default:s((()=>[c("中国石化")])),_:1}),d(z,null,{default:s((()=>[c("6.44")])),_:1})])),_:1}),d(F,{class:"list-item-inner"},{default:s((()=>[d(z,null,{default:s((()=>[c("100.00")])),_:1}),d(z,null,{default:s((()=>[c("6.44")])),_:1})])),_:1}),d(F,{class:"list-item-inner"},{default:s((()=>[d(z,null,{default:s((()=>[c("2024-07-10")])),_:1}),d(z,null,{default:s((()=>[c("14:15:48")])),_:1})])),_:1}),d(F,{class:"list-item-inner"},{default:s((()=>[d(z,null,{default:s((()=>[c("买入委托")])),_:1}),d(z,null,{default:s((()=>[c("已成")])),_:1})])),_:1})])),_:1})])),_:1})])),_:1})])),_:1},512),[[_,3===V.value]]),o(d(F,{class:"search-list"},{default:s((()=>[(u(),r(p,null,m(D,((l,t)=>d(F,{class:"list-item",key:l.name,onClick:l=>{0===t&&k()}},{default:s((()=>[d(z,null,{default:s((()=>[c(g(l.name),1)])),_:2},1024),d(j,{name:"arrow-right",size:"18",color:"#c7c7cc"})])),_:2},1032,["onClick"]))),64))])),_:1},512),[[_,4===V.value]])])),_:1})])),_:1})}}},[["__scopeId","data-v-820688fd"]]);export{z as default};
