import{Z as t,q as e,aq as a,cn as s,aj as o,x as r,y as i,z as l,A as n,C as d,H as u,D as c,E as m,F as h,G as _,I as p,J as f,O as w,K as g,bo as y}from"./index-DXZh4TrB.js";import{_ as x}from"./uni-nav-bar.HncgR8y-.js";import{r as C}from"./uni-app.es.BpJJwZA9.js";import{_ as $}from"./u-tabs.DzSHb1aZ.js";import{_ as k}from"./u-sticky.o8oTDHnR.js";import{_ as D}from"./u-loadmore.C3IN5QC8.js";import{_ as R}from"./u-empty.CV-Dq1M_.js";import{_ as b}from"./u-input.DdaX0e4J.js";import{_ as v}from"./u-modal.CZVrP39W.js";import{l as j,m as I,w as P,a as T,n as A}from"./index.qP49loVj.js";import{_ as W}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.CnM6HZsg.js";import"./uni-status-bar.BUxDt1qs.js";import"./u-line.DIn8oqBr.js";import"./u-loading-icon.D8mSLZ2D.js";import"./u-icon.CtR5oXjZ.js";import"./u-popup.8Kefg54u.js";import"./u-transition.TbX_Iwh5.js";import"./u-status-bar.v5iZ-5k5.js";const V=W({data(){return{show:!1,showWithdrawModal:!1,title:this.$t("orderRecord.text.initialAmount"),value:"",paywd:"",valueid:"",currentItem:null,money:"",page:1,per_page:1,status:"loadmore",tagData:[],indexs:0,list1:[],withdrawProfitAllocation:{},withdrawStatus:!1,currenrtOrderId:"",currentWithdrawBalance:0,withdrawPayload:{}}},methods:{back:t,async getWithdrawProfitAllocationData(){const{data:t}=await j();t&&(this.withdrawProfitAllocation=t,this.withdrawStatus=this.isTimeInRange(t.profit_start_time,t.profit_end_time))},isTimeInRange(t,e){const a=(new Date).getTime(),s=new Date,o=new Date;s.setHours(t.substring(0,2),t.substring(3,5),0),o.setHours(e.substring(0,2),e.substring(3,5),0);const r=s.getTime(),i=o.getTime();return a>=r&&a<=i},timeStampConvert(t){var e=new Date(1e3*t);return e.getFullYear()+"-"+(e.getMonth()+1)+"-"+e.getDate()+" "+e.getHours()+":"+e.getMinutes()},timeStampConverts(t){var e=new Date(1e3*t);return e.getFullYear()+"-"+(e.getMonth()+1)+"-"+e.getDate()+" "+e.getHours()+":"+e.getMinutes()},cancel(){this.show=!1,this.value=""},confirm(){I({id:this.currentItem.id,money:this.value,paywd:this.paywd}).then((t=>{0===t.error?(this.show=!1,this.tagData=[],this.Collect(this.currentItem.status),this.getUser(),this.value=""):e({title:t.message,icon:"none",duration:2e3})}))},superaddition(t){this.currentItem=t,this.show=!0,this.getUser()},openWithdrawDialog(t,e){this.currenrtOrderId=t;const a=e-this.withdrawProfitAllocation.min_account;this.currentWithdrawBalance=a<=0?0:e,this.showWithdrawModal=!0},closeWithdrawDialog(){this.showWithdrawModal=!1,this.currentWithdrawBalance=0},async withdrawConfirm(){if(this.withdrawStatus){const t=await P({order_id:this.currenrtOrderId,paywd:this.withdrawPayload.paywd,money:this.withdrawPayload.money});t&&(this.tagData=[],this.Collect(this.currentItem.status),e({title:t.message,icon:"none",duration:2e3}),this.closeWithdrawDialog())}else e({title:"非提盈时间",icon:"none",duration:2e3})},async apicomModuleFn(){const t=await T();let e=[];t.data&&(e=[{name:this.$t("orderRecord.text.following"),status:1},{name:this.$t("orderRecord.text.over"),status:3}],0===t.data.module.auto_gd_Audit&&(e.unshift({name:this.$t("orderRecord.text.awaitingReview"),status:0}),e.push({name:this.$t("orderRecord.text.rejected"),status:5})),this.list1=e,this.Collect())},onReachBottom(){this.page>=this.per_page&&(this.status="nomore"),this.page>=this.per_page||(this.status="loading",this.page++,setTimeout((()=>{this.Collect(this.currentItem.status)}),0))},click(t){A({page:1,status:t.status}).then((t=>{this.tagData=[],this.tagData=t.data.list.list.data,this.per_page=t.data.list.list.last_page,this.currentItem=this.tagData[0]}))},muns(t){a("/pages/asset-details/asset-details",{id:t})},Collect(t=null){A({page:1,status:null!==t?t:this.list1[this.indexs].status}).then((t=>{for(let e=0;e<t.data.list.list.data.length;e++)this.tagData.push(t.data.list.list.data[e]);this.per_page=t.data.list.list.last_page,this.currentItem=this.tagData[0]}))},getUser(){s().then((t=>{this.money=t.data.money}))}},created(){this.getWithdrawProfitAllocationData(),this.apicomModuleFn()},onPullDownRefresh(){this.page=1,this.tagData=[],this.Collect(this.currentItem.status),setTimeout((function(){o()}),1e3)}},[["render",function(t,e,a,s,o,j){const I=C(r("uni-nav-bar"),x),P=C(r("up-tabs"),$),T=C(r("up-sticky"),k),A=d,W=w,V=C(r("up-loadmore"),D),M=C(r("up-empty"),R),U=C(r("up-input"),b),B=C(r("up-modal"),v);return i(),l(A,null,{default:n((()=>[u(I,{title:t.$t("page.title.orderRecord"),"status-bar":"","background-color":"#ff3c36",color:"#fff","left-icon":"left",fixed:"",border:!1,onClickLeft:j.back},null,8,["title","onClickLeft"]),u(T,{bgColor:"#f5f5f5"},{default:n((()=>[u(P,{list:o.list1,onClick:j.click,lineWidth:"50",lineHeight:"2",lineColor:"#FE4530",scrollable:!1},null,8,["list","onClick"])])),_:1}),(i(!0),c(h,null,m(o.tagData,((e,a)=>(i(),l(A,{class:"page_list",key:a},{default:n((()=>[u(A,{class:"page_list_item"},{default:n((()=>[g("div",{class:"onkeyBuy"},f(2==e.order_type?t.$t("common.text.dailySmartInvestment"):t.$t("common.text.oneClickSmartInvestment")),1),u(A,{class:"list_headerto"},{default:n((()=>[u(A,{class:"list_header_left"},{default:n((()=>[p(f(t.$t("orderRecord.text.orderNumber"))+f(e.order_sn),1)])),_:2},1024)])),_:2},1024),u(A,{class:"list_header"},{default:n((()=>[u(A,{class:"list_header_left"},{default:n((()=>[u(W,null,{default:n((()=>[p(f(t.$t("orderRecord.text.productName"))+f(e.product_name),1)])),_:2},1024)])),_:2},1024)])),_:2},1024),u(A,{class:"list_header"},{default:n((()=>[u(A,{class:"list_header_left"},{default:n((()=>[0===e.status?(i(),l(W,{key:0},{default:n((()=>[p(f(t.$t("orderRecord.text.copyTradeCycle"))+f(t.$t("orderRecord.text.awaitingReview")),1)])),_:1})):_("",!0),0!==e.status?(i(),l(W,{key:1},{default:n((()=>[p(f(t.$t("orderRecord.text.copyTradeCycle"))+f(e.create_time_text)+"~"+f(j.timeStampConverts(e.fundendtime)),1)])),_:2},1024)):_("",!0)])),_:2},1024)])),_:2},1024),u(A,{class:"list_headerto"},{default:n((()=>[u(A,{class:"list_header_left"},{default:n((()=>[p(f(t.$t("orderRecord.text.tutor"))+f(e.trader_texta),1)])),_:2},1024),u(A,{class:"list_header_left"},{default:n((()=>[p(f(t.$t("common.text.orderAmount"))+f(e.money)+f(t.$t("common.text.currencyUnit")),1)])),_:2},1024)])),_:2},1024),0===e.status&&2===e.order_type||1==e.status||6==e.status?(i(),l(A,{key:0,class:"list_header"},{default:n((()=>[u(A,{class:"list_header_left"},{default:n((()=>[u(W,null,{default:n((()=>[p(f(t.$t("orderRecord.text.initialAmount"))+f(e.initial_money)+f(t.$t("common.text.currencyUnit")),1)])),_:2},1024)])),_:2},1024),u(A,{class:"list_header_left"},{default:n((()=>[u(W,null,{default:n((()=>[p(f(t.$t("orderRecord.text.additionalAmount"))+f(e.add_money)+f(t.$t("common.text.currencyUnit")),1)])),_:2},1024)])),_:2},1024)])),_:2},1024)):_("",!0),u(A,{class:"list_header"},{default:n((()=>[u(A,{class:"list_header_left"},{default:n((()=>[u(W,null,{default:n((()=>[p(f(t.$t("orderRecord.text.proportionOfProfit"))+f(e.commission)+"%",1)])),_:2},1024)])),_:2},1024),u(A,{class:"list_header_left"},{default:n((()=>[u(W,null,{default:n((()=>[p(f(t.$t("orderRecord.text.applicationTime"))+f(e.create_time_text),1)])),_:2},1024)])),_:2},1024)])),_:2},1024),0===e.status||1===e.status||3===e.status||6===e.status||7===e.status?(i(),l(A,{key:1,class:"list_heais"},{default:n((()=>[1===e.status||6===e.status?(i(),l(A,{key:0,class:"list_heais_withdraw",onClick:y((t=>j.openWithdrawDialog(e.id,e.balance)),["stop"])},{default:n((()=>[p(" 提盈 ")])),_:2},1032,["onClick"])):_("",!0),0===e.status&&2===e.order_type||1===e.status&&3===e.order_type||6===e.status&&3===e.order_type?(i(),l(A,{key:1,class:"list_heais_lse",onClick:y((t=>j.superaddition(e)),["stop"])},{default:n((()=>[p(f(t.$t("orderRecord.text.append")),1)])),_:2},1032,["onClick"])):_("",!0),u(A,{class:"list_heais_rse",onClick:t=>j.muns(e.id)},{default:n((()=>[p(f(t.$t("orderRecord.text.checkTheDetails")),1)])),_:2},1032,["onClick"])])),_:2},1024)):_("",!0)])),_:2},1024)])),_:2},1024)))),128)),o.tagData.length>0?(i(),l(V,{key:0,status:o.status,"loading-text":t.$t("common.text.loading"),"loadmore-text":t.$t("common.text.loadMore"),"nomore-text":t.$t("common.text.noMore"),onClick:j.onReachBottom},null,8,["status","loading-text","loadmore-text","nomore-text","onClick"])):_("",!0),0==o.tagData.length?(i(),l(M,{key:1,mode:"data",width:"200","margin-top":"520rpx",icon:"/static/Vertretung/noMoreData@2x.png",text:t.$t("common.text.noData")},null,8,["text"])):_("",!0),u(B,{show:o.showWithdrawModal,title:"提盈金额",showCancelButton:!0,confirmText:o.withdrawStatus?"确定":"非提盈时间",cancelText:t.$t("common.text.cancel"),onConfirm:j.withdrawConfirm,onCancel:j.closeWithdrawDialog},{default:n((()=>[u(A,{class:"munsys withdraw-modal-box"},{default:n((()=>[u(A,{class:"withdrawl-input"},{default:n((()=>[u(U,{placeholder:`提盈金额最低为${o.withdrawProfitAllocation.min_account}元`,type:"number",border:"surround",modelValue:o.withdrawPayload.money,"onUpdate:modelValue":e[0]||(e[0]=t=>o.withdrawPayload.money=t)},null,8,["placeholder","modelValue"])])),_:1}),u(A,{class:"form_middle_tip"},{default:n((()=>[u(A,null,{default:n((()=>[p("可提金额："),u(W,{class:"withdraw_num"},{default:n((()=>[p(f(o.currentWithdrawBalance),1)])),_:1})])),_:1}),u(W,null,{default:n((()=>[o.withdrawProfitAllocation.residue_num>0?(i(),l(W,{key:0},{default:n((()=>[p("每日剩余提盈次数："),u(W,{class:"buy_num"},{default:n((()=>[p(f(o.withdrawProfitAllocation.residue_num),1)])),_:1})])),_:1})):_("",!0)])),_:1})])),_:1}),u(A,{class:"withdrawl-input"},{default:n((()=>[u(U,{type:"password",placeholder:"提盈密码",border:"surround",modelValue:o.withdrawPayload.paywd,"onUpdate:modelValue":e[1]||(e[1]=t=>o.withdrawPayload.paywd=t)},null,8,["modelValue"])])),_:1}),u(A,{class:"withdraw_time"},{default:n((()=>[p(f(`提盈时间：${o.withdrawProfitAllocation.profit_start_time} ~\n          ${o.withdrawProfitAllocation.profit_end_time}（交易日）`),1)])),_:1})])),_:1})])),_:1},8,["show","confirmText","cancelText","onConfirm","onCancel"]),o.currentItem?(i(),l(B,{key:2,show:o.show,title:o.title+o.currentItem.initial_money+t.$t("common.text.currencyUnit"),showCancelButton:!0,confirmText:t.$t("common.text.confirm"),cancelText:t.$t("common.text.cancel"),onConfirm:j.confirm,onCancel:j.cancel},{default:n((()=>[u(A,{class:"munsys"},{default:n((()=>[u(U,{placeholder:t.$t("orderRecord.text.tip1"),type:"number",border:"surround",modelValue:o.value,"onUpdate:modelValue":e[2]||(e[2]=t=>o.value=t)},null,8,["placeholder","modelValue"]),u(U,{placeholder:t.$t("orderRecord.text.tip2"),type:"password",border:"surround",modelValue:o.paywd,"onUpdate:modelValue":e[3]||(e[3]=t=>o.paywd=t),style:{"margin-top":"16rpx"}},null,8,["placeholder","modelValue"]),u(A,{class:"munsys_zis"},{default:n((()=>[p(f(t.$t("orderRecord.text.tip3"))+f(o.money.account)+" "+f(t.$t("common.text.currencyUnit")),1)])),_:1}),u(A,{class:"munsys_ziss"},{default:n((()=>[p(f(t.$t("orderRecord.text.tip4")),1)])),_:1})])),_:1})])),_:1},8,["show","title","confirmText","cancelText","onConfirm","onCancel"])):_("",!0)])),_:1})}],["__scopeId","data-v-7a413621"]]);export{V as default};
