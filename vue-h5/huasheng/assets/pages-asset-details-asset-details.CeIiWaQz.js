import{_ as t}from"./uni-nav-bar.CxJfmD0V.js";import{Z as e,ap as a,x as l,y as s,z as i,A as d,C as n,H as u,I as _,J as o,a9 as r,G as c,D as m,E as f,F as p,cm as g,K as x,O as h,b7 as D}from"./index-v5N4FCa5.js";import{r as y}from"./uni-app.es.BBb7LdIV.js";import{_ as b}from"./u-icon.C6i9Eu8q.js";import{E as $,F as k,G as v}from"./index.Dm55e1Sd.js";import{_ as C}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.DUeGXWcA.js";import"./uni-status-bar.D590Ci0c.js";const F=C({data:()=>({value:1,page:1,pageData:"",numberDetail:"",details:"",sellMax:"",list:[],paf:"",id:""}),onLoad(t){var e=this;e.id=t.id,$({id:t.id,page:e.page}).then((t=>{e.pageData=t.data.form,e.numberDetail=t.data.statistics,e.sellMax=t.data.statistics.hold_money})),k({id:t.id}).then((t=>{e.details=t.data.form})),e.getTradeL()},mounted(){},methods:{back:e,handleEnter(t){"Enter"===t.key&&this.handleButtonClick()},handleButtonClick(){this.nextge()},lextge(){this.page>1&&(this.page=this.page-1,this.getTradeL())},nextge(){this.paf.last_page>this.page&&(this.page=this.page+1,this.getTradeL())},getTradeL(){v({id:this.id,page:this.page,type:1,order_type:2}).then((t=>{this.list=t.data.list.data,this.paf=t.data.list}))},skip(t){a("/pages/buying-result/buying-result",{id:t,title:"订单信息",estimate:1})}}},[["render",function(e,a,$,k,v,C){const F=y(l("uni-nav-bar"),t),T=n,j=h,I=y(l("up-icon"),b),U=D;return s(),i(T,{style:{"background-color":"#fcfcfc",height:"100vh"}},{default:d((()=>[u(F,{title:e.$t("page.title.assetDetails"),"status-bar":"","background-color":"#FF3C36",color:"#fff","left-icon":"left",fixed:"",border:!1,onClickLeft:C.back},null,8,["title","onClickLeft"]),u(T,{class:"asset_detail_header"},{default:d((()=>[u(T,null,{default:d((()=>[u(T,null,{default:d((()=>[_(o(2==v.details.order_type?e.$t("common.text.dailySmartInvestment"):e.$t("common.text.oneClickSmartInvestment")),1)])),_:1}),u(j,null,{default:d((()=>[_(o(e.$t("assetDetails.text.dataUpdatedTheNextDay")),1)])),_:1})])),_:1}),u(T,null,{default:d((()=>[_(o(v.pageData.create_time_text),1)])),_:1})])),_:1}),u(T,{class:"earnings_details_box"},{default:d((()=>[u(T,null,{default:d((()=>[u(T,{class:"earnings_details"},{default:d((()=>[u(T,{class:"sset_details"},{default:d((()=>[_(o(e.$t("assetDetails.text.amountAvailableForInvestment")),1)])),_:1}),u(T,{class:"total_amount"},{default:d((()=>[_(o(v.numberDetail.dtje),1),u(j,null,{default:d((()=>[_(o(e.$t("common.text.currencyUnit")),1)])),_:1})])),_:1})])),_:1}),u(T,{class:"earnings_data"},{default:d((()=>[u(T,{class:"mao"},{default:d((()=>[u(T,{class:"rnings_da"},{default:d((()=>[_(o(e.$t("assetDetails.text.orderInvestmentAmount")),1)])),_:1}),u(T,{class:"gs_dat"},{default:d((()=>[_(o(v.pageData&&v.pageData.money?Number(v.pageData.money).toFixed(2):"0.00")+o(e.$t("common.text.currencyUnit")),1)])),_:1})])),_:1}),u(T,{class:"mao"},{default:d((()=>[u(T,{class:"rnings_da"},{default:d((()=>[_(o(e.$t("assetDetails.text.orderRevenue"))+"("+o(e.$t("common.text.currencyUnit"))+") ",1)])),_:1}),u(T,{class:"gs_dat",style:r(v.numberDetail.hold_earning&&v.numberDetail.hold_earning<0?"color: #178634":"")},{default:d((()=>[_(o(v.pageData&&v.pageData.balance?Number(v.pageData.balance).toFixed(2):"0.00")+o(e.$t("common.text.currencyUnit")),1)])),_:1},8,["style"])])),_:1}),u(T,{class:"mao"},{default:d((()=>[u(T,{class:"rnings_da"},{default:d((()=>[_(o(e.$t("assetDetails.text.holdingYield")),1)])),_:1}),u(T,{class:"gs_dat",style:r(v.numberDetail.hold_earning&&v.numberDetail.hold_earning<0?"color: #178634":"")},{default:d((()=>[_(o(v.numberDetail&&v.numberDetail.hold_earning?Number(v.numberDetail.hold_earning).toFixed(2):"0.00")+"% ",1)])),_:1},8,["style"])])),_:1})])),_:1})])),_:1})])),_:1}),u(T,{class:"asset_detail"},{default:d((()=>[u(T,{class:"header-box"},{default:d((()=>[u(T,null,{default:d((()=>[_(o(e.$t("assetDetails.text.orderDetails")),1)])),_:1}),u(T,null,{default:d((()=>[_(o(v.details.status_txt),1)])),_:1})])),_:1}),u(T,{class:"asset_detail_list_item"},{default:d((()=>[u(T,null,{default:d((()=>[_(o(e.$t("assetDetails.text.orderNumber")),1)])),_:1}),u(T,null,{default:d((()=>[_(o(v.details.order_sn),1)])),_:1})])),_:1}),u(T,{class:"asset_detail_list_item"},{default:d((()=>[u(T,null,{default:d((()=>[_(o(e.$t("assetDetails.text.creationTime")),1)])),_:1}),u(T,null,{default:d((()=>[_(o(v.details.create_time_text),1)])),_:1})])),_:1}),u(T,{class:"asset_detail_list_item"},{default:d((()=>[u(T,null,{default:d((()=>[_(o(e.$t("assetDetails.text.reviewTime")),1)])),_:1}),u(T,null,{default:d((()=>[_(o(v.details.confirm_time_text),1)])),_:1})])),_:1}),u(T,{class:"asset_detail_list_item"},{default:d((()=>[u(T,null,{default:d((()=>[_(o(e.$t("assetDetails.text.tradingDays")),1)])),_:1}),u(T,null,{default:d((()=>[_(o(v.details.cycle),1)])),_:1})])),_:1}),u(T,{class:"asset_detail_list_item"},{default:d((()=>[u(T,null,{default:d((()=>[_(o(e.$t("assetDetails.text.endTime")),1)])),_:1}),u(T,{style:{color:"#178634"}},{default:d((()=>[_(o(v.details.fundendtime_time_text),1)])),_:1})])),_:1}),u(T,{class:"asset_detail_list_item"},{default:d((()=>[u(T,null,{default:d((()=>[_(o(e.$t("assetDetails.text.copyTradeInstructor")),1)])),_:1}),u(T,null,{default:d((()=>[_(o(v.details.trader),1)])),_:1})])),_:1}),u(T,{class:"asset_detail_list_item"},{default:d((()=>[u(T,null,{default:d((()=>[_(o(e.$t("assetDetails.text.tutorFeeRate")),1)])),_:1}),u(T,{style:{color:"#178634"}},{default:d((()=>[_(o(v.details.commission)+"%",1)])),_:1})])),_:1}),1===v.pageData.status||6===v.pageData.status?(s(),i(T,{key:0,class:"asset_detail_list_item"},{default:d((()=>[u(T,null,{default:d((()=>[_(o(e.$t("assetDetails.text.initialAmount")),1)])),_:1}),u(T,null,{default:d((()=>[_(o(v.pageData.initial_money?Number(v.pageData.initial_money).toFixed(2):"0.00")+o(e.$t("common.text.currencyUnit")),1)])),_:1})])),_:1})):c("",!0),1===v.pageData.status||6===v.pageData.status?(s(),i(T,{key:1,class:"asset_detail_list_item"},{default:d((()=>[u(T,null,{default:d((()=>[_(o(e.$t("assetDetails.text.additionalAmount")),1)])),_:1}),u(T,null,{default:d((()=>[_(o(v.details.add_money?Number(v.details.add_money).toFixed(2):"0.00")+o(e.$t("common.text.currencyUnit")),1)])),_:1})])),_:1})):c("",!0),0!==v.details.settlement_time?(s(),i(T,{key:2,class:"asset_detail_list_item"},{default:d((()=>[u(T,null,{default:d((()=>[_(o(e.$t("assetDetails.text.settlementTime")),1)])),_:1}),u(T,{style:{color:"#fd4630"}},{default:d((()=>[_(o(v.details.settlement_time_text),1)])),_:1})])),_:1})):c("",!0)])),_:1}),v.list.length?(s(),i(T,{key:0,class:"purchase_record"},{default:d((()=>[u(T,{class:"chase_rec"},{default:d((()=>[_(o(e.$t("assetDetails.text.orderRecord")),1)])),_:1}),(s(!0),m(p,null,f(v.list,((t,a)=>(s(),i(T,{class:"purchase_record_list_item",key:a,onClick:e=>C.skip(t.id)},{default:d((()=>[u(T,{class:"purchase_record_list_item_left"},{default:d((()=>[u(T,null,{default:d((()=>[1==t.status?(s(),i(T,{key:0},{default:d((()=>[_(o(e.$t("assetDetails.text.buyItIn")),1)])),_:1})):c("",!0),2==t.status?(s(),i(T,{key:1},{default:d((()=>[_("卖出")])),_:1})):c("",!0),3==t.status?(s(),i(T,{key:2},{default:d((()=>[_("已结算")])),_:1})):c("",!0),u(T,null,{default:d((()=>[_(o(t.date),1)])),_:2},1024)])),_:2},1024),u(T,{class:"purchase_record_list_ite"},{default:d((()=>[_(o(t.stockname),1)])),_:2},1024)])),_:2},1024),u(T,{class:"purchase_record_list_item_right"},{default:d((()=>[_(o(t.fy)+o(e.$t("common.text.currencyUnit"))+" ",1),u(I,{name:"arrow-right",color:"#EAE4E4",size:"38rpx"})])),_:2},1024)])),_:2},1032,["onClick"])))),128)),u(T,{class:"list_pagination"},{default:d((()=>[u(T,{class:"last_page",onClick:C.lextge},{default:d((()=>[_(o(e.$t("common.text.previousPage")),1)])),_:1},8,["onClick"]),u(U,{type:"number",placeholder:"",modelValue:v.page,"onUpdate:modelValue":a[0]||(a[0]=t=>v.page=t),onKeydown:g(C.handleEnter,["enter"])},null,8,["modelValue","onKeydown"]),u(T,{class:"last_page",onClick:C.nextge},{default:d((()=>[_(o(e.$t("common.text.nextPage")),1)])),_:1},8,["onClick"]),u(T,{class:"total_page"},{default:d((()=>[x("span",null,o(e.$t("common.text.total")),1),x("span",null,o(v.paf.last_page),1),x("span",null,o(e.$t("common.text.page")),1)])),_:1})])),_:1})])),_:1})):c("",!0)])),_:1})}],["__scopeId","data-v-61031abe"]]);export{F as default};
