import{Z as t,q as e,aq as a,ck as s,ai as o,x as i,y as r,z as l,A as n,C as d,H as u,D as c,E as m,F as h,G as _,I as p,J as f,O as w,K as g,a$ as y}from"./index-CV-MPaTy.js";import{_ as x}from"./uni-nav-bar.kuvB3EA8.js";import{r as $}from"./uni-app.es.BfUhxxZm.js";import{_ as C}from"./u-tabs.DFLqhZts.js";import{_ as k}from"./u-sticky.8CP4VGgj.js";import{_ as D}from"./u-loadmore.D3by1wn8.js";import{_ as R}from"./u-empty.CL9PgWfq.js";import{_ as b}from"./u-input.CjfLEWZh.js";import{_ as v}from"./u-modal.BoHJGIxw.js";import{l as j,m as I,w as T,a as P,n as W}from"./index.r8z51F9e.js";import{_ as A}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.CRfdyxgB.js";import"./uni-status-bar.B6IF_cY_.js";import"./u-line.D-07wiMt.js";import"./u-loading-icon.DO6c51az.js";import"./u-icon.Be9T_C7C.js";import"./u-popup.Bh9eEkv7.js";import"./u-transition.FHV81czB.js";import"./u-status-bar.t2oXwsnh.js";const V=A({data(){return{show:!1,showWithdrawModal:!1,title:this.$t("orderRecord.text.initialAmount"),value:"",paywd:"",valueid:"",currentItem:null,money:"",page:1,per_page:1,status:"loadmore",tagData:[],indexs:0,list1:[],withdrawProfitAllocation:{},withdrawStatus:!1,currenrtOrderId:"",currentWithdrawBalance:0,withdrawPayload:{}}},methods:{back:t,async getWithdrawProfitAllocationData(){const{data:t}=await j();t&&(this.withdrawProfitAllocation=t,this.withdrawStatus=this.isTimeInRange(t.profit_start_time,t.profit_end_time))},isTimeInRange(t,e){const a=(new Date).getTime(),s=new Date,o=new Date;s.setHours(t.substring(0,2),t.substring(3,5),0),o.setHours(e.substring(0,2),e.substring(3,5),0);const i=s.getTime(),r=o.getTime();return a>=i&&a<=r},timeStampConvert(t){var e=new Date(1e3*t);return e.getFullYear()+"-"+(e.getMonth()+1)+"-"+e.getDate()+" "+e.getHours()+":"+e.getMinutes()},timeStampConverts(t){var e=new Date(1e3*t);return e.getFullYear()+"-"+(e.getMonth()+1)+"-"+e.getDate()+" "+e.getHours()+":"+e.getMinutes()},cancel(){this.show=!1,this.value=""},confirm(){I({id:this.currentItem.id,money:this.value,paywd:this.paywd}).then((t=>{0===t.error?(this.show=!1,this.tagData=[],this.Collect(this.currentItem.status),this.getUser(),this.value=""):e({title:t.message,icon:"none",duration:2e3})}))},superaddition(t){this.currentItem=t,this.show=!0,this.getUser()},openWithdrawDialog(t,e){this.currenrtOrderId=t;const a=e-this.withdrawProfitAllocation.min_account;this.currentWithdrawBalance=a<=0?0:e,this.showWithdrawModal=!0},closeWithdrawDialog(){this.showWithdrawModal=!1,this.currentWithdrawBalance=0},async withdrawConfirm(){if(this.withdrawStatus){const t=await T({order_id:this.currenrtOrderId,paywd:this.withdrawPayload.paywd,money:this.withdrawPayload.money});t&&(this.tagData=[],this.Collect(this.currentItem.status),e({title:t.message,icon:"none",duration:2e3}),this.closeWithdrawDialog())}else e({title:"非提盈时间",icon:"none",duration:2e3})},async apicomModuleFn(){const t=await P();let e=[];t.data&&(e=[{name:this.$t("orderRecord.text.following"),status:1},{name:this.$t("orderRecord.text.over"),status:3}],0===t.data.module.auto_gd_Audit&&(e.unshift({name:this.$t("orderRecord.text.awaitingReview"),status:0}),e.push({name:this.$t("orderRecord.text.rejected"),status:5})),this.list1=e,this.Collect())},onReachBottom(){this.page>=this.per_page&&(this.status="nomore"),this.page>=this.per_page||(this.status="loading",this.page++,setTimeout((()=>{this.Collect(this.currentItem.status)}),0))},click(t){W({page:1,status:t.status}).then((t=>{this.tagData=[],this.tagData=t.data.list.list.data,this.per_page=t.data.list.list.last_page,this.currentItem=this.tagData[0]}))},muns(t){a("/pages/asset-details/asset-details",{id:t})},Collect(t=null){W({page:1,status:null!==t?t:this.list1[this.indexs].status}).then((t=>{for(let e=0;e<t.data.list.list.data.length;e++)this.tagData.push(t.data.list.list.data[e]);this.per_page=t.data.list.list.last_page,this.currentItem=this.tagData[0]}))},getUser(){s().then((t=>{this.money=t.data.money}))}},created(){this.getWithdrawProfitAllocationData(),this.apicomModuleFn()},onPullDownRefresh(){this.page=1,this.tagData=[],this.Collect(this.currentItem.status),setTimeout((function(){o()}),1e3)}},[["render",function(t,e,a,s,o,j){const I=$(i("uni-nav-bar"),x),T=$(i("up-tabs"),C),P=$(i("up-sticky"),k),W=d,A=w,V=$(i("up-loadmore"),D),M=$(i("up-empty"),R),U=$(i("up-input"),b),S=$(i("up-modal"),v);return r(),l(W,null,{default:n((()=>[u(I,{title:t.$t("page.title.orderRecord"),"status-bar":"","background-color":"#ff3c36",color:"#fff","left-icon":"left",fixed:"",border:!1,onClickLeft:j.back},null,8,["title","onClickLeft"]),u(P,{bgColor:"#f5f5f5"},{default:n((()=>[u(T,{list:o.list1,onClick:j.click,lineWidth:"50",lineHeight:"2",lineColor:"#FE4530",scrollable:!1},null,8,["list","onClick"])])),_:1}),(r(!0),c(h,null,m(o.tagData,((e,a)=>(r(),l(W,{class:"page_list",key:a},{default:n((()=>[u(W,{class:"page_list_item"},{default:n((()=>[g("div",{class:"onkeyBuy"},f(2==e.order_type?t.$t("common.text.dailySmartInvestment"):t.$t("common.text.oneClickSmartInvestment")),1),u(W,{class:"list_headerto"},{default:n((()=>[u(W,{class:"list_header_left"},{default:n((()=>[p(f(t.$t("orderRecord.text.orderNumber"))+f(e.order_sn),1)])),_:2},1024)])),_:2},1024),u(W,{class:"list_header"},{default:n((()=>[u(W,{class:"list_header_left"},{default:n((()=>[u(A,null,{default:n((()=>[p(f(t.$t("orderRecord.text.productName"))+f(e.product_name),1)])),_:2},1024)])),_:2},1024)])),_:2},1024),u(W,{class:"list_header"},{default:n((()=>[u(W,{class:"list_header_left"},{default:n((()=>[0===e.status?(r(),l(A,{key:0},{default:n((()=>[p(f(t.$t("orderRecord.text.copyTradeCycle"))+f(t.$t("orderRecord.text.awaitingReview")),1)])),_:1})):_("",!0),0!==e.status?(r(),l(A,{key:1},{default:n((()=>[p(f(t.$t("orderRecord.text.copyTradeCycle"))+f(e.create_time_text)+"~"+f(j.timeStampConverts(e.fundendtime)),1)])),_:2},1024)):_("",!0)])),_:2},1024)])),_:2},1024),u(W,{class:"list_headerto"},{default:n((()=>[u(W,{class:"list_header_left"},{default:n((()=>[p(f(t.$t("orderRecord.text.tutor"))+f(e.trader_texta),1)])),_:2},1024),u(W,{class:"list_header_left"},{default:n((()=>[p(f(t.$t("common.text.orderAmount"))+f(e.money)+f(t.$t("common.text.currencyUnit")),1)])),_:2},1024)])),_:2},1024),1==e.status||6==e.status?(r(),l(W,{key:0,class:"list_header"},{default:n((()=>[u(W,{class:"list_header_left"},{default:n((()=>[u(A,null,{default:n((()=>[p(f(t.$t("orderRecord.text.initialAmount"))+f(e.initial_money)+f(t.$t("common.text.currencyUnit")),1)])),_:2},1024)])),_:2},1024),u(W,{class:"list_header_left"},{default:n((()=>[u(A,null,{default:n((()=>[p(f(t.$t("orderRecord.text.additionalAmount"))+f(e.add_money)+f(t.$t("common.text.currencyUnit")),1)])),_:2},1024)])),_:2},1024)])),_:2},1024)):_("",!0),u(W,{class:"list_header"},{default:n((()=>[u(W,{class:"list_header_left"},{default:n((()=>[u(A,null,{default:n((()=>[p(f(t.$t("orderRecord.text.proportionOfProfit"))+f(e.commission)+"%",1)])),_:2},1024)])),_:2},1024),u(W,{class:"list_header_left"},{default:n((()=>[u(A,null,{default:n((()=>[p(f(t.$t("orderRecord.text.applicationTime"))+f(e.create_time_text),1)])),_:2},1024)])),_:2},1024)])),_:2},1024),1==e.status||3==e.status||6==e.status||7==e.status?(r(),l(W,{key:1,class:"list_heais"},{default:n((()=>[1==e.status||6==e.status?(r(),l(W,{key:0,class:"list_heais_withdraw",onClick:y((t=>j.openWithdrawDialog(e.id,e.balance)),["stop"])},{default:n((()=>[p(" 提盈 ")])),_:2},1032,["onClick"])):_("",!0),1===e.status||6===e.status?(r(),l(W,{key:1,class:"list_heais_lse",onClick:y((t=>j.superaddition(e)),["stop"])},{default:n((()=>[p(f(t.$t("orderRecord.text.append")),1)])),_:2},1032,["onClick"])):_("",!0),u(W,{class:"list_heais_rse",onClick:t=>j.muns(e.id)},{default:n((()=>[p(f(t.$t("orderRecord.text.checkTheDetails")),1)])),_:2},1032,["onClick"])])),_:2},1024)):_("",!0)])),_:2},1024)])),_:2},1024)))),128)),o.tagData.length>0?(r(),l(V,{key:0,status:o.status,"loading-text":t.$t("common.text.loading"),"loadmore-text":t.$t("common.text.loadMore"),"nomore-text":t.$t("common.text.noMore"),onClick:j.onReachBottom},null,8,["status","loading-text","loadmore-text","nomore-text","onClick"])):_("",!0),0==o.tagData.length?(r(),l(M,{key:1,mode:"data",width:"200","margin-top":"520rpx",icon:"/static/Vertretung/noMoreData@2x.png",text:t.$t("common.text.noData")},null,8,["text"])):_("",!0),u(S,{show:o.showWithdrawModal,title:"提盈金额",showCancelButton:!0,confirmText:o.withdrawStatus?"确定":"非提盈时间",cancelText:t.$t("common.text.cancel"),onConfirm:j.withdrawConfirm,onCancel:j.closeWithdrawDialog},{default:n((()=>[u(W,{class:"munsys withdraw-modal-box"},{default:n((()=>[u(W,{class:"withdrawl-input"},{default:n((()=>[u(U,{placeholder:`提盈金额最低为${o.withdrawProfitAllocation.min_account}元`,type:"number",border:"surround",modelValue:o.withdrawPayload.money,"onUpdate:modelValue":e[0]||(e[0]=t=>o.withdrawPayload.money=t)},null,8,["placeholder","modelValue"])])),_:1}),u(W,{class:"form_middle_tip"},{default:n((()=>[u(W,null,{default:n((()=>[p("可提金额："),u(A,{class:"withdraw_num"},{default:n((()=>[p(f(o.currentWithdrawBalance),1)])),_:1})])),_:1}),u(A,null,{default:n((()=>[o.withdrawProfitAllocation.residue_num>0?(r(),l(A,{key:0},{default:n((()=>[p("每日剩余提盈次数："),u(A,{class:"buy_num"},{default:n((()=>[p(f(o.withdrawProfitAllocation.residue_num),1)])),_:1})])),_:1})):_("",!0)])),_:1})])),_:1}),u(W,{class:"withdrawl-input"},{default:n((()=>[u(U,{type:"password",placeholder:"提盈密码",border:"surround",modelValue:o.withdrawPayload.paywd,"onUpdate:modelValue":e[1]||(e[1]=t=>o.withdrawPayload.paywd=t)},null,8,["modelValue"])])),_:1}),u(W,{class:"withdraw_time"},{default:n((()=>[p(f(`提盈时间：${o.withdrawProfitAllocation.profit_start_time} ~\n          ${o.withdrawProfitAllocation.profit_end_time}（交易日）`),1)])),_:1})])),_:1})])),_:1},8,["show","confirmText","cancelText","onConfirm","onCancel"]),o.currentItem?(r(),l(S,{key:2,show:o.show,title:o.title+o.currentItem.initial_money+t.$t("common.text.currencyUnit"),showCancelButton:!0,confirmText:t.$t("common.text.confirm"),cancelText:t.$t("common.text.cancel"),onConfirm:j.confirm,onCancel:j.cancel},{default:n((()=>[u(W,{class:"munsys"},{default:n((()=>[u(U,{placeholder:t.$t("orderRecord.text.tip1"),type:"number",border:"surround",modelValue:o.value,"onUpdate:modelValue":e[2]||(e[2]=t=>o.value=t)},null,8,["placeholder","modelValue"]),u(U,{placeholder:t.$t("orderRecord.text.tip2"),type:"number",border:"surround",modelValue:o.paywd,"onUpdate:modelValue":e[3]||(e[3]=t=>o.paywd=t),style:{"margin-top":"16rpx"}},null,8,["placeholder","modelValue"]),u(W,{class:"munsys_zis"},{default:n((()=>[p(f(t.$t("orderRecord.text.tip3"))+f(o.money.account)+" "+f(t.$t("common.text.currencyUnit")),1)])),_:1}),u(W,{class:"munsys_ziss"},{default:n((()=>[p(f(t.$t("orderRecord.text.tip4")),1)])),_:1})])),_:1})])),_:1},8,["show","title","confirmText","cancelText","onConfirm","onCancel"])):_("",!0)])),_:1})}],["__scopeId","data-v-afcc4d59"]]);export{V as default};
