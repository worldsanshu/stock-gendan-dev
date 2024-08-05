import{V as t,x as e,y as a,z as s,A as l,q as r,C as u,H as o,Y as i,Z as n,I as d,J as f,G as c}from"./index-BpfZrajd.js";import{_}from"./uni-nav-bar.BZQCfLjz.js";import{r as m}from"./uni-app.es.C3RDKkKF.js";import{_ as w}from"./u-tag.COU-YiZl.js";import{_ as p,a as y}from"./uni-list.DAoDA03R.js";import{_ as h}from"./u-skeleton.B5Rn11cu.js";import{o as g}from"./wallet.Bdn4ZSqo.js";import{_ as x}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.CkblPvCD.js";import"./uni-status-bar.CQB75Tz1.js";import"./u-icon.B3VNGynq.js";import"./u-transition.CT0rDcrB.js";import"./uni-badge.CSdzwKoB.js";const v=x({__name:"user-center-withdraw-detail",props:{id:String},setup(x){const v=x,k=t({}),$=t(!0);return async function(){const{error:t,message:e,data:a}=await g({withdraw_id:v.id});t?await r({title:e,icon:"none"}):(k.value=a,$.value=!1)}(),(t,r)=>{const g=m(e("uni-nav-bar"),_),x=u,v=m(e("up-tag"),w),j=m(e("uni-list-item"),p),R=m(e("uni-list"),y),b=m(e("up-skeleton"),h);return a(),s(x,null,{default:l((()=>[o(g,{title:"提现详情","status-bar":"","background-color":"#ff3c36",color:"#fff","left-icon":"left",onClickLeft:i(n)},null,8,["onClickLeft"]),o(b,{class:"p-30",rows:"8","rows-height":"40",loading:$.value},{default:l((()=>[o(x,{class:"u-flex-column u-flex-items-center p-30"},{default:l((()=>[o(x,{class:"align-center mt-10"},{default:l((()=>[o(x,{class:"align-center"},{default:l((()=>[o(x,{class:"font-30-bold"},{default:l((()=>[d(f(k.value.num),1)])),_:1}),o(x,{class:"font-20-bold ml-10"},{default:l((()=>[d(f(k.value.bank_text),1)])),_:1})])),_:1})])),_:1}),o(x,{class:"mt-10"},{default:l((()=>[o(x,{class:"font-14-grey ml-20"},{default:l((()=>[d(f(`${k.value.money}${t.$t("common.text.currencyUnit")}`),1)])),_:1})])),_:1}),o(x,{class:"mt-10 withdraw-type"},{default:l((()=>[0===k.value.status?(a(),s(v,{key:0,text:t.$t("withdrawalsRecord.text.processing"),size:"mini",type:"primary"},null,8,["text"])):1===k.value.status?(a(),s(v,{key:1,text:t.$t("withdrawalsRecord.text.withdrawalSuccessful"),size:"mini",type:"success"},null,8,["text"])):2===k.value.status?(a(),s(v,{key:2,text:t.$t("withdrawalsRecord.text.withdrawalFailed"),size:"mini",type:"error"},null,8,["text"])):c("",!0),3===k.value.status?(a(),s(v,{key:3,text:t.$t("withdrawalsRecord.text.cashWithdrawalRefund"),size:"mini",type:"primary"},null,8,["text"])):c("",!0)])),_:1})])),_:1}),o(R,null,{default:l((()=>[o(j,null,{header:l((()=>[o(x,{class:"font-14"},{default:l((()=>[d("订单号")])),_:1})])),footer:l((()=>[o(x,{class:"font-12-grey"},{default:l((()=>[d(f(k.value.order_no),1)])),_:1})])),_:1}),o(j,null,{header:l((()=>[o(x,{class:"font-14"},{default:l((()=>[d("实际提现金额")])),_:1})])),footer:l((()=>[o(x,{class:"font-12-grey"},{default:l((()=>[d(f(k.value.real_money),1)])),_:1})])),_:1}),o(j,null,{header:l((()=>[o(x,{class:"font-14"},{default:l((()=>[d(f(t.$t("rechargeDetails.text.state")),1)])),_:1})])),footer:l((()=>[0===k.value.status?(a(),s(x,{key:0,class:"font-12-grey"},{default:l((()=>[d(f(t.$t("withdrawalsRecord.text.processing")),1)])),_:1})):1===k.value.status?(a(),s(x,{key:1,class:"font-12-grey"},{default:l((()=>[d(f(t.$t("withdrawalsRecord.text.withdrawalSuccessful")),1)])),_:1})):2===k.value.status?(a(),s(x,{key:2,class:"font-12-grey"},{default:l((()=>[d(f(t.$t("withdrawalsRecord.text.withdrawalFailed")),1)])),_:1})):3===k.value.status?(a(),s(x,{key:3,class:"font-12-grey"},{default:l((()=>[d(f(t.$t("withdrawalsRecord.text.cashWithdrawalRefund")),1)])),_:1})):c("",!0)])),_:1}),1===k.value.status?(a(),s(j,{key:0},{header:l((()=>[o(x,{class:"font-14"},{default:l((()=>[d("手续费")])),_:1})])),footer:l((()=>[o(x,{class:"font-12-grey"},{default:l((()=>[d(f(k.value.fee),1)])),_:1})])),_:1})):c("",!0),o(j,null,{header:l((()=>[o(x,{class:"font-14"},{default:l((()=>[d(f(t.$t("rechargeDetails.text.time")),1)])),_:1})])),footer:l((()=>[o(x,{class:"font-12-grey"},{default:l((()=>[d(f(k.value.create_time),1)])),_:1})])),_:1}),k.value.remark?(a(),s(j,{key:1},{header:l((()=>[o(x,{class:"font-14"},{default:l((()=>[d("备注")])),_:1})])),footer:l((()=>[o(x,{class:"font-12-grey"},{default:l((()=>[d(f(k.value.remark),1)])),_:1})])),_:1})):c("",!0)])),_:1})])),_:1},8,["loading"])])),_:1})}}},[["__scopeId","data-v-67a276f6"]]);export{v as default};
