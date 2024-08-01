import{V as e,x as t,y as a,z as l,A as s,q as r,C as n,H as u,Y as o,Z as i,bW as c,I as f,J as d,G as _,D as y,bI as g,F as m}from"./index-BWJszhAQ.js";import{_ as p}from"./uni-nav-bar.DYJ8qjGb.js";import{r as h}from"./uni-app.es.C_vI-sDC.js";import{_ as x}from"./u-tag.DJd6PGEs.js";import{_ as v,a as $}from"./uni-list.rQpyiege.js";import{_ as D}from"./uni-icons.7JXZxnLM.js";import{_ as k}from"./u-skeleton.BpiOxW8-.js";import{f as b}from"./wallet.ClthBGwo.js";import{_ as j}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-status-bar.NjuetTv4.js";import"./u-icon.DXT6fckP.js";import"./u-transition.DJ9sB_Wk.js";import"./uni-badge.jE8iy_gJ.js";const w=j({__name:"user-center-recharge-detail",props:{id:String},setup(j){const w=j,C=e({}),R=e(!0);return async function(){const{error:e,message:t,data:a}=await b({id:w.id});e?await r({title:t,icon:"none"}):(C.value=a,R.value=!1)}(),(e,r)=>{const b=h(t("uni-nav-bar"),p),j=n,w=h(t("up-tag"),x),z=h(t("uni-list-item"),v),I=h(t("uni-icons"),D),A=h(t("uni-list"),$),N=h(t("up-skeleton"),k);return a(),l(j,null,{default:s((()=>[u(b,{title:e.$t("rechargeDetails.text.title"),"status-bar":"","background-color":"#ff3c36",color:"#fff","right-icon":"more-filled","left-icon":"left",onClickLeft:r[0]||(r[0]=e=>o(i)(2)),onClickRight:o(c)},null,8,["title","onClickRight"]),u(N,{class:"p-30",rows:"8","rows-height":"40",loading:R.value},{default:s((()=>[u(j,{class:"u-flex-column u-flex-items-center p-30"},{default:s((()=>[u(j,null,{default:s((()=>[f(d(e.$t("rechargeDetails.text.quantity")),1)])),_:1}),u(j,{class:"align-center mt-10"},{default:s((()=>[u(j,{class:"align-center"},{default:s((()=>[u(j,{class:"font-30-bold"},{default:s((()=>[f(d(C.value.num),1)])),_:1}),u(j,{class:"font-20-bold ml-10"},{default:s((()=>[f(d(C.value.payment_name),1)])),_:1})])),_:1}),u(j,{class:"font-14-grey ml-20"},{default:s((()=>[f(d(`(${C.value.money}${e.$t("common.text.currencyUnit")})`),1)])),_:1})])),_:1}),u(j,{class:"mt-10"},{default:s((()=>[0===C.value.status?(a(),l(w,{key:0,size:"mini",text:e.$t("rechargeDetails.text.awaitingReview"),type:"primary"},null,8,["text"])):1===C.value.status?(a(),l(w,{key:1,size:"mini",text:e.$t("rechargeDetails.text.success"),type:"success"},null,8,["text"])):2===C.value.status?(a(),l(w,{key:2,size:"mini",text:e.$t("rechargeDetails.text.fail"),type:"error"},null,8,["text"])):_("",!0)])),_:1})])),_:1}),u(A,null,{default:s((()=>[u(z,null,{header:s((()=>[u(j,{class:"font-14"},{default:s((()=>[f(d(e.$t("rechargeDetails.text.state")),1)])),_:1})])),footer:s((()=>[0===C.value.status?(a(),l(j,{key:0,class:"font-12-grey"},{default:s((()=>[f(d(e.$t("rechargeDetails.text.awaitingReview")),1)])),_:1})):1===C.value.status?(a(),l(j,{key:1,class:"font-12-grey"},{default:s((()=>[f(d(e.$t("rechargeDetails.text.success")),1)])),_:1})):2===C.value.status?(a(),l(j,{key:2,class:"font-12-grey"},{default:s((()=>[f(d(e.$t("rechargeDetails.text.fail")),1)])),_:1})):_("",!0)])),_:1}),u(z,null,{header:s((()=>[u(j,{class:"font-14"},{default:s((()=>[f(d(e.$t("rechargeDetails.text.type")),1)])),_:1})])),footer:s((()=>["currency"===C.value.payment_type?(a(),l(j,{key:0,class:"font-12-grey"},{default:s((()=>[f(d(e.$t("rechargeDetails.text.digitalCurrency")),1)])),_:1})):_("",!0),"bank"===C.value.payment_type?(a(),l(j,{key:1,class:"font-12-grey"},{default:s((()=>[f(d(e.$t("rechargeDetails.text.unionPay")),1)])),_:1})):_("",!0),"wechatpay"===C.value.payment_type?(a(),l(j,{key:2,class:"font-12-grey"},{default:s((()=>[f(d(e.$t("rechargeDetails.text.weChatPay")),1)])),_:1})):_("",!0),"alipay"===C.value.payment_type?(a(),l(j,{key:3,class:"font-12-grey"},{default:s((()=>[f(d(e.$t("rechargeDetails.text.aliPay")),1)])),_:1})):_("",!0)])),_:1}),"currency"===C.value.payment_type?(a(),y(m,{key:0},[u(z,null,{header:s((()=>[u(j,{class:"font-14"},{default:s((()=>[f(d(e.$t("rechargeDetails.text.currency")),1)])),_:1})])),footer:s((()=>[u(j,{class:"font-12-grey"},{default:s((()=>[f(d(C.value.payment_name),1)])),_:1})])),_:1}),u(z,null,{header:s((()=>[u(j,{class:"font-14"},{default:s((()=>[f(d(e.$t("rechargeDetails.text.transferAddress")),1)])),_:1})])),footer:s((()=>[u(j,{class:"align-center"},{default:s((()=>[u(j,{class:"font-12-grey"},{default:s((()=>[f(d(C.value.address),1)])),_:1}),u(I,{class:"ml-10","custom-prefix":"fc-iconfont",type:"icon-copy",onClick:r[1]||(r[1]=e=>o(g)(C.value.address))})])),_:1})])),_:1}),u(z,null,{header:s((()=>[u(j,{class:"font-14"},{default:s((()=>[f(d(e.$t("rechargeDetails.text.TXIDAddress")),1)])),_:1})])),footer:s((()=>[u(j,{class:"align-center"},{default:s((()=>[u(j,{class:"font-12-grey"},{default:s((()=>[f(d(C.value.txid),1)])),_:1}),u(I,{class:"ml-10","custom-prefix":"fc-iconfont",type:"icon-copy",onClick:r[2]||(r[2]=e=>o(g)(C.value.txid))})])),_:1})])),_:1}),u(z,null,{header:s((()=>[u(j,{class:"font-14"},{default:s((()=>[f(d(e.$t("rechargeDetails.text.exchangeRate")),1)])),_:1})])),footer:s((()=>[u(j,{class:"font-12-grey"},{default:s((()=>[f(d(C.value.exchange_rate),1)])),_:1})])),_:1})],64)):_("",!0),"bank"===C.value.payment_type?(a(),y(m,{key:1},[u(z,null,{header:s((()=>[u(j,{class:"font-14"},{default:s((()=>[f(d(e.$t("rechargeDetails.text.payee")),1)])),_:1})])),footer:s((()=>[u(j,{class:"font-12-grey"},{default:s((()=>[f(d(C.value.payee),1)])),_:1})])),_:1}),u(z,null,{header:s((()=>[u(j,{class:"font-14"},{default:s((()=>[f(d(e.$t("rechargeDetails.text.paymentCardNumber")),1)])),_:1})])),footer:s((()=>[u(j,{class:"font-12-grey"},{default:s((()=>[f(d(C.value.card_number),1)])),_:1})])),_:1}),u(z,null,{header:s((()=>[u(j,{class:"font-14"},{default:s((()=>[f(d(e.$t("rechargeDetails.text.bank")),1)])),_:1})])),footer:s((()=>[u(j,{class:"font-12-grey"},{default:s((()=>[f(d(C.value.bank_name),1)])),_:1})])),_:1}),u(z,null,{header:s((()=>[u(j,{class:"font-14"},{default:s((()=>[f(d(e.$t("rechargeDetails.text.transferCardNumber")),1)])),_:1})])),footer:s((()=>[u(j,{class:"font-12-grey"},{default:s((()=>[f(d(C.value.transfer_number),1)])),_:1})])),_:1})],64)):_("",!0),u(z,null,{header:s((()=>[u(j,{class:"font-14"},{default:s((()=>[f(d(e.$t("rechargeDetails.text.time")),1)])),_:1})])),footer:s((()=>[u(j,{class:"font-12-grey"},{default:s((()=>[f(d(C.value.create_time),1)])),_:1})])),_:1})])),_:1})])),_:1},8,["loading"])])),_:1})}}},[["__scopeId","data-v-f52dd1a4"]]);export{w as default};
