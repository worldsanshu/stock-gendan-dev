import{V as t,x as e,y as a,z as s,q as l,B as r,C as u,H as o,Y as i,Z as n,I as d,J as f,G as c}from"./index-hU8OKPmH.js";import{_}from"./uni-nav-bar.U3Cmk77Z.js";import{r as m}from"./uni-app.es.DADla7ur.js";import{_ as w}from"./u-tag.DQbIOaQN.js";import{_ as p,a as y}from"./uni-list.DsLuk3Ly.js";import{_ as h}from"./u-skeleton.BLpUwz8I.js";import{o as g}from"./wallet.VcNaQDUL.js";import{_ as v}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.DgCYXD5d.js";import"./uni-status-bar.BC4SStPU.js";import"./u-icon.BX8bEo6J.js";import"./u-transition.hFcAyfbx.js";import"./uni-badge.CJQLJhSm.js";const x=v({__name:"user-center-withdraw-detail",props:{id:String},setup(v){const x=v,k=t({}),j=t(!0);return async function(){const{error:t,message:e,data:a}=await g({withdraw_id:x.id});t?await l({title:e,icon:"none"}):(k.value=a,j.value=!1)}(),(t,l)=>{const g=m(e("uni-nav-bar"),_),v=r,x=m(e("up-tag"),w),$=m(e("uni-list-item"),p),R=m(e("uni-list"),y),b=m(e("up-skeleton"),h);return u(),a(v,null,{default:s((()=>[o(g,{title:"提现详情","status-bar":"","background-color":"#ff3c36",color:"#fff","left-icon":"left",onClickLeft:i(n)},null,8,["onClickLeft"]),o(b,{class:"p-30",rows:"8","rows-height":"40",loading:j.value},{default:s((()=>[o(v,{class:"u-flex-column u-flex-items-center p-30"},{default:s((()=>[o(v,{class:"align-center mt-10"},{default:s((()=>[o(v,{class:"align-center"},{default:s((()=>[o(v,{class:"font-30-bold"},{default:s((()=>[d(f(k.value.num),1)])),_:1}),o(v,{class:"font-20-bold ml-10"},{default:s((()=>[d(f(k.value.bank_text),1)])),_:1})])),_:1})])),_:1}),o(v,{class:"mt-10"},{default:s((()=>[o(v,{class:"font-14-grey ml-20"},{default:s((()=>[d(f(`${k.value.money}${t.$t("common.text.currencyUnit")}`),1)])),_:1})])),_:1}),o(v,{class:"mt-10 withdraw-type"},{default:s((()=>[0===k.value.status?(u(),a(x,{key:0,text:t.$t("withdrawalsRecord.text.processing"),size:"mini",type:"primary"},null,8,["text"])):1===k.value.status?(u(),a(x,{key:1,text:t.$t("withdrawalsRecord.text.withdrawalSuccessful"),size:"mini",type:"success"},null,8,["text"])):2===k.value.status?(u(),a(x,{key:2,text:t.$t("withdrawalsRecord.text.withdrawalFailed"),size:"mini",type:"error"},null,8,["text"])):c("",!0),3===k.value.status?(u(),a(x,{key:3,text:t.$t("withdrawalsRecord.text.cashWithdrawalRefund"),size:"mini",type:"primary"},null,8,["text"])):c("",!0)])),_:1})])),_:1}),o(R,null,{default:s((()=>[o($,null,{header:s((()=>[o(v,{class:"font-14"},{default:s((()=>[d("订单号")])),_:1})])),footer:s((()=>[o(v,{class:"font-12-grey"},{default:s((()=>[d(f(k.value.order_no),1)])),_:1})])),_:1}),o($,null,{header:s((()=>[o(v,{class:"font-14"},{default:s((()=>[d("实际提现金额")])),_:1})])),footer:s((()=>[o(v,{class:"font-12-grey"},{default:s((()=>[d(f(k.value.real_money),1)])),_:1})])),_:1}),o($,null,{header:s((()=>[o(v,{class:"font-14"},{default:s((()=>[d(f(t.$t("rechargeDetails.text.state")),1)])),_:1})])),footer:s((()=>[0===k.value.status?(u(),a(v,{key:0,class:"font-12-grey"},{default:s((()=>[d(f(t.$t("withdrawalsRecord.text.processing")),1)])),_:1})):1===k.value.status?(u(),a(v,{key:1,class:"font-12-grey"},{default:s((()=>[d(f(t.$t("withdrawalsRecord.text.withdrawalSuccessful")),1)])),_:1})):2===k.value.status?(u(),a(v,{key:2,class:"font-12-grey"},{default:s((()=>[d(f(t.$t("withdrawalsRecord.text.withdrawalFailed")),1)])),_:1})):3===k.value.status?(u(),a(v,{key:3,class:"font-12-grey"},{default:s((()=>[d(f(t.$t("withdrawalsRecord.text.cashWithdrawalRefund")),1)])),_:1})):c("",!0)])),_:1}),1===k.value.status?(u(),a($,{key:0},{header:s((()=>[o(v,{class:"font-14"},{default:s((()=>[d("手续费")])),_:1})])),footer:s((()=>[o(v,{class:"font-12-grey"},{default:s((()=>[d(f(k.value.fee),1)])),_:1})])),_:1})):c("",!0),o($,null,{header:s((()=>[o(v,{class:"font-14"},{default:s((()=>[d(f(t.$t("rechargeDetails.text.time")),1)])),_:1})])),footer:s((()=>[o(v,{class:"font-12-grey"},{default:s((()=>[d(f(k.value.create_time),1)])),_:1})])),_:1}),k.value.remark?(u(),a($,{key:1},{header:s((()=>[o(v,{class:"font-14"},{default:s((()=>[d("备注")])),_:1})])),footer:s((()=>[o(v,{class:"font-12-grey"},{default:s((()=>[d(f(k.value.remark),1)])),_:1})])),_:1})):c("",!0)])),_:1})])),_:1},8,["loading"])])),_:1})}}},[["__scopeId","data-v-67a276f6"]]);export{x as default};
