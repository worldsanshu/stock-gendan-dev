import{V as e,x as t,y as a,z as l,q as s,B as r,C as n,H as u,Y as o,Z as c,bY as i,I as f,J as d,G as _,D as m,aV as g,F as y}from"./index-DugkB_-4.js";import{_ as p}from"./uni-nav-bar.DGUi_IvM.js";import{r as h}from"./uni-app.es.CX-CC-lz.js";import{_ as x}from"./u-tag.DLf8_YdH.js";import{_ as v,a as k}from"./uni-list.Di66fBPU.js";import{_ as $}from"./uni-icons.D9BxN9k6.js";import{_ as D}from"./u-skeleton.BYaqrno_.js";import{f as b}from"./wallet.BMBhKyJb.js";import{_ as j}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-status-bar.D_XiF9iL.js";import"./u-icon.Ca1c56qc.js";import"./u-transition.bUAwPu-p.js";import"./uni-badge.veoJrIAT.js";const w=j({__name:"user-center-recharge-detail",props:{id:String},setup(j){const w=j,C=e({}),R=e(!0);return async function(){const{error:e,message:t,data:a}=await b({id:w.id});e?await s({title:t,icon:"none"}):(C.value=a,R.value=!1)}(),(e,s)=>{const b=h(t("uni-nav-bar"),p),j=r,w=h(t("up-tag"),x),z=h(t("uni-list-item"),v),I=h(t("uni-icons"),$),N=h(t("uni-list"),k),q=h(t("up-skeleton"),D);return n(),a(j,null,{default:l((()=>[u(b,{title:e.$t("rechargeDetails.text.title"),"status-bar":"","background-color":"#ff3c36",color:"#fff","right-icon":"more-filled","left-icon":"left",onClickLeft:o(c),onClickRight:o(i)},null,8,["title","onClickLeft","onClickRight"]),u(q,{class:"p-30",rows:"8","rows-height":"40",loading:R.value},{default:l((()=>[u(j,{class:"u-flex-column u-flex-items-center p-30"},{default:l((()=>[u(j,null,{default:l((()=>[f(d(e.$t("rechargeDetails.text.quantity")),1)])),_:1}),u(j,{class:"align-center mt-10"},{default:l((()=>[u(j,{class:"align-center"},{default:l((()=>[u(j,{class:"font-30-bold"},{default:l((()=>[f(d(C.value.num),1)])),_:1}),u(j,{class:"font-20-bold ml-10"},{default:l((()=>[f(d(C.value.payment_name),1)])),_:1})])),_:1}),u(j,{class:"font-14-grey ml-20"},{default:l((()=>[f(d(`(${C.value.money}${e.$t("common.text.currencyUnit")})`),1)])),_:1})])),_:1}),u(j,{class:"mt-10"},{default:l((()=>[0===C.value.status?(n(),a(w,{key:0,size:"mini",text:e.$t("rechargeDetails.text.awaitingReview"),type:"primary"},null,8,["text"])):1===C.value.status?(n(),a(w,{key:1,size:"mini",text:e.$t("rechargeDetails.text.success"),type:"success"},null,8,["text"])):2===C.value.status?(n(),a(w,{key:2,size:"mini",text:e.$t("rechargeDetails.text.fail"),type:"error"},null,8,["text"])):_("",!0)])),_:1})])),_:1}),u(N,null,{default:l((()=>[u(z,null,{header:l((()=>[u(j,{class:"font-14"},{default:l((()=>[f(d(e.$t("rechargeDetails.text.state")),1)])),_:1})])),footer:l((()=>[0===C.value.status?(n(),a(j,{key:0,class:"font-12-grey"},{default:l((()=>[f(d(e.$t("rechargeDetails.text.awaitingReview")),1)])),_:1})):1===C.value.status?(n(),a(j,{key:1,class:"font-12-grey"},{default:l((()=>[f(d(e.$t("rechargeDetails.text.success")),1)])),_:1})):2===C.value.status?(n(),a(j,{key:2,class:"font-12-grey"},{default:l((()=>[f(d(e.$t("rechargeDetails.text.fail")),1)])),_:1})):_("",!0)])),_:1}),u(z,null,{header:l((()=>[u(j,{class:"font-14"},{default:l((()=>[f(d(e.$t("rechargeDetails.text.type")),1)])),_:1})])),footer:l((()=>[u(j,{class:"font-12-grey"},{default:l((()=>[f(d(C.value.payment_name),1)])),_:1})])),_:1}),"currency"===C.value.payment_type?(n(),m(y,{key:0},[u(z,null,{header:l((()=>[u(j,{class:"font-14"},{default:l((()=>[f(d(e.$t("rechargeDetails.text.currency")),1)])),_:1})])),footer:l((()=>[u(j,{class:"font-12-grey"},{default:l((()=>[f(d(C.value.payment_name),1)])),_:1})])),_:1}),u(z,null,{header:l((()=>[u(j,{class:"font-14"},{default:l((()=>[f(d(e.$t("rechargeDetails.text.transferAddress")),1)])),_:1})])),footer:l((()=>[u(j,{class:"align-center"},{default:l((()=>[u(j,{class:"font-12-grey"},{default:l((()=>[f(d(C.value.address),1)])),_:1}),u(I,{class:"ml-10","custom-prefix":"fc-iconfont",type:"icon-copy",onClick:s[0]||(s[0]=e=>o(g)(C.value.address))})])),_:1})])),_:1}),u(z,null,{header:l((()=>[u(j,{class:"font-14"},{default:l((()=>[f(d(e.$t("rechargeDetails.text.TXIDAddress")),1)])),_:1})])),footer:l((()=>[u(j,{class:"align-center"},{default:l((()=>[u(j,{class:"font-12-grey"},{default:l((()=>[f(d(C.value.txid),1)])),_:1}),u(I,{class:"ml-10","custom-prefix":"fc-iconfont",type:"icon-copy",onClick:s[1]||(s[1]=e=>o(g)(C.value.txid))})])),_:1})])),_:1}),u(z,null,{header:l((()=>[u(j,{class:"font-14"},{default:l((()=>[f(d(e.$t("rechargeDetails.text.exchangeRate")),1)])),_:1})])),footer:l((()=>[u(j,{class:"font-12-grey"},{default:l((()=>[f(d(C.value.exchange_rate),1)])),_:1})])),_:1})],64)):_("",!0),"bank"===C.value.payment_type?(n(),m(y,{key:1},[u(z,null,{header:l((()=>[u(j,{class:"font-14"},{default:l((()=>[f(d(e.$t("rechargeDetails.text.payee")),1)])),_:1})])),footer:l((()=>[u(j,{class:"font-12-grey"},{default:l((()=>[f(d(C.value.payee),1)])),_:1})])),_:1}),u(z,null,{header:l((()=>[u(j,{class:"font-14"},{default:l((()=>[f(d(e.$t("rechargeDetails.text.paymentCardNumber")),1)])),_:1})])),footer:l((()=>[u(j,{class:"font-12-grey"},{default:l((()=>[f(d(C.value.card_number),1)])),_:1})])),_:1}),u(z,null,{header:l((()=>[u(j,{class:"font-14"},{default:l((()=>[f(d(e.$t("rechargeDetails.text.bank")),1)])),_:1})])),footer:l((()=>[u(j,{class:"font-12-grey"},{default:l((()=>[f(d(C.value.bank_name),1)])),_:1})])),_:1}),u(z,null,{header:l((()=>[u(j,{class:"font-14"},{default:l((()=>[f(d(e.$t("rechargeDetails.text.transferCardNumber")),1)])),_:1})])),footer:l((()=>[u(j,{class:"font-12-grey"},{default:l((()=>[f(d(C.value.transfer_number),1)])),_:1})])),_:1})],64)):_("",!0),u(z,null,{header:l((()=>[u(j,{class:"font-14"},{default:l((()=>[f(d(e.$t("rechargeDetails.text.time")),1)])),_:1})])),footer:l((()=>[u(j,{class:"font-12-grey"},{default:l((()=>[f(d(C.value.create_time),1)])),_:1})])),_:1}),C.value.remark?(n(),a(z,{key:2},{header:l((()=>[u(j,{class:"font-14"},{default:l((()=>[f("备注")])),_:1})])),footer:l((()=>[u(j,{class:"font-12-grey"},{default:l((()=>[f(d(C.value.remark),1)])),_:1})])),_:1})):_("",!0)])),_:1})])),_:1},8,["loading"])])),_:1})}}},[["__scopeId","data-v-c64b5194"]]);export{w as default};