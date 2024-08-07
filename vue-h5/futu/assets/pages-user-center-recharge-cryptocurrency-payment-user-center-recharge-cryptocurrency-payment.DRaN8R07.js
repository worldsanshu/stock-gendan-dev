import{R as e,V as t,U as a,x as r,y as l,z as u,A as o,q as n,C as s,H as i,Y as c,Z as m,bY as d,D as f,I as p,J as g,aV as y,c3 as v,F as h,E as _,G as x,bW as b,c4 as C,c5 as R,N as $}from"./index-_qVRbLMl.js";import{_ as j}from"./uni-nav-bar.CqtfX-i-.js";import{r as k}from"./uni-app.es.CwLmRD9h.js";import{_ as w}from"./u-input.CrhwEoxg.js";import{_ as V}from"./uni-icons.CkUbBeV1.js";import{_ as D,a as I}from"./uni-list.B-F74tct.js";import{_ as T,a as U}from"./u-form.D51hNWnV.js";import{_ as A}from"./u-button.BhmJCeJ7.js";import{_ as E}from"./u-popup.CU0Onh68.js";import{d as F,r as q}from"./wallet.CrnvYlvL.js";import{_ as B}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-status-bar.BBpSB2OF.js";import"./u-icon.WTlR_o2P.js";import"./uni-badge.1g4AJKj_.js";import"./u-line.xBYwZJD9.js";import"./u-loading-icon.C0chiosN.js";import"./u-transition.D61Fk1Af.js";import"./u-status-bar.DVnN7CDW.js";const H=B({__name:"user-center-recharge-cryptocurrency-payment",setup(B){const{t:H}=e(),N=t(),Q=a({num:"",txid:""}),Y={num:{type:"string",required:!0,message:H("virtualCurrencyRecharge.text.rechargeQuantityErrorTip"),trigger:["blur","change"]},txid:{type:"string",message:H("virtualCurrencyRecharge.text.TXIDAddressErrorTip"),trigger:["blur","change"]}},z=t(!1),G=t([]),J=t(),L=t(0),X=t();async function Z(){if(parseFloat(G.value[L.value].max_money)<parseFloat(Q.num))n({title:"充值金额已超过最大可充值金额"+G.value[L.value].max_money+"，请重新输入",icon:"none"});else try{await N.value.validate();const{confirm:e}=await C({title:H("common.text.systemHint"),content:H("virtualCurrencyRecharge.text.rechargeTip")});if(e){const{error:e,message:t,data:a}=await q({type:"currency",payment_id:J.value.id,currency:J.value.name,address:J.value.address,money:Q.num*Number(J.value.exchange_rate),num:Q.num,txid:Q.txid,transfer:"transfer"});if(e)return void(await n({title:t,icon:"none"}));R({id:a.id})}}catch(e){e.length&&await n({title:e[0].message,icon:"none"})}}return async function(){const{error:e,data:t,message:a}=await F();e?await n({title:a,icon:"none"}):(G.value=t.list,t.list.length&&(J.value=t.list[0],X.value=t.list[1]))}(),(e,t)=>{const a=k(r("uni-nav-bar"),j),n=k(r("up-input"),w),C=$,R=s,F=k(r("uni-icons"),V),q=k(r("uni-list-item"),D),B=k(r("uni-list"),I),H=k(r("up-form-item"),T),X=k(r("up-form"),U),P=k(r("up-button"),A),W=k(r("up-popup"),E);return l(),u(R,null,{default:o((()=>[i(a,{title:e.$t("virtualCurrencyRecharge.text.title"),"status-bar":"","background-color":"#ff3c36",color:"#fff",fixed:"","left-icon":"left","right-icon":"more",onClickLeft:c(m),onClickRight:c(d)},null,8,["title","onClickLeft","onClickRight"]),J.value?(l(),f(h,{key:0},[i(R,{class:"page-body"},{default:o((()=>[i(n,{modelValue:Q.money,"onUpdate:modelValue":t[0]||(t[0]=e=>Q.money=e),placeholder:`${J.value.name} (${J.value.agreement})`,"input-align":"center",disabled:"","disabled-color":"#fff","placeholder-style":"color: '#333'","suffix-icon":"arrow-down",onClick:t[1]||(t[1]=e=>z.value=!0)},null,8,["modelValue","placeholder"]),i(R,{class:"code mt-50"},{default:o((()=>[i(C,{mode:"",onClick:t[2]||(t[2]=e=>{return t=G.value[L.value].logo_url,void b({urls:[t]});var t}),src:G.value[L.value].logo_url,style:{width:"500rpx",height:"500rpx"}},null,8,["src"])])),_:1}),i(B,{class:"mt-50"},{default:o((()=>[i(q,null,{header:o((()=>[i(R,{class:"font-14"},{default:o((()=>[p(g(e.$t("virtualCurrencyRecharge.text.rechargeAddress")),1)])),_:1})])),body:o((()=>[i(R,{class:"font-12-grey u-flex-fill"},{default:o((()=>[p(g(J.value.address),1)])),_:1})])),footer:o((()=>[i(F,{"custom-prefix":"fc-iconfont",type:"icon-copy",onClick:t[3]||(t[3]=e=>c(y)(J.value.address))})])),_:1}),i(q,null,{header:o((()=>[i(R,{class:"font-14"},{default:o((()=>[p(g(e.$t("virtualCurrencyRecharge.text.minimumDeposit")),1)])),_:1})])),body:o((()=>[i(R,{class:"font-12-grey u-flex-fill"},{default:o((()=>[p(g(J.value.min_money),1)])),_:1})])),_:1}),i(q,null,{header:o((()=>[i(R,{class:"font-14"},{default:o((()=>[p(g(e.$t("virtualCurrencyRecharge.text.exchangeRate")),1)])),_:1})])),body:o((()=>[i(R,{class:"font-12-grey u-flex-fill"},{default:o((()=>[p(g(J.value.exchange_rate),1)])),_:1})])),_:1})])),_:1}),i(R,{class:"info mt-50"},{default:o((()=>[i(R,{class:"mt-10 font-12-grey"},{default:o((()=>[p(g(e.$t("virtualCurrencyRecharge.text.tip1")),1)])),_:1}),i(R,{class:"mt-10 font-12-grey"},{default:o((()=>[p(g(e.$t("virtualCurrencyRecharge.text.tip2")),1)])),_:1}),i(R,{class:"mt-10 font-12-grey"},{default:o((()=>[p(g(e.$t("virtualCurrencyRecharge.text.tip2")),1)])),_:1}),i(R,{class:"mt-10 font-12-grey"},{default:o((()=>[p(g(e.$t("virtualCurrencyRecharge.text.tip3")),1)])),_:1}),i(R,{class:"mt-10 font-12-grey"},{default:o((()=>[p(g(e.$t("virtualCurrencyRecharge.text.tip4")),1)])),_:1})])),_:1}),i(X,{class:"form mt-50","label-width":"120",labelPosition:"top",model:Q,rules:Y,ref_key:"formRef",ref:N},{default:o((()=>[i(H,{label:e.$t("virtualCurrencyRecharge.text.rechargeQuantity"),required:"",prop:"num",borderBottom:""},{default:o((()=>[i(n,{type:"number",modelValue:Q.num,"onUpdate:modelValue":t[4]||(t[4]=e=>Q.num=e),placeholder:Y.num.message},v({_:2},[Q.num?{name:"suffix",fn:o((()=>[p(g(e.$t("virtualCurrencyRecharge.text.balance"))+g(Q.num*Number(J.value.exchange_rate))+" "+g(e.$t("common.text.currencyUnit")),1)])),key:"0"}:void 0]),1032,["modelValue","placeholder"])])),_:1},8,["label"]),i(H,{label:e.$t("virtualCurrencyRecharge.text.TXIDAddress"),prop:"txid",borderBottom:""},{default:o((()=>[i(n,{modelValue:Q.txid,"onUpdate:modelValue":t[5]||(t[5]=e=>Q.txid=e),placeholder:Y.txid.message},null,8,["modelValue","placeholder"])])),_:1},8,["label"])])),_:1},8,["model"]),i(P,{class:"mt-50",color:"rgb(255, 69, 0)",text:"提交",onClick:Z})])),_:1}),i(W,{show:z.value,onClose:t[6]||(t[6]=e=>z.value=!1),closeable:"",round:"15rpx"},{default:o((()=>[i(R,{class:"popup-content"},{default:o((()=>[i(R,{class:"font-14-bold text-center"},{default:o((()=>[p(g(e.$t("common.text.submit")),1)])),_:1}),i(B,{class:"mt-30"},{default:o((()=>[(l(!0),f(h,null,_(G.value,((t,a)=>(l(),u(q,{title:t.name,note:t.agreement,thumb:t.logo_url,"thumb-size":"70rpx",clickable:"",key:t.id,"right-text":`${e.$t("virtualCurrencyRecharge.text.minimumRecharge")}${t.min_money}`,onClick:e=>function(e,t){console.log("看看",t),J.value=e,z.value=!1,L.value=t}(t,a)},null,8,["title","note","thumb","right-text","onClick"])))),128))])),_:1})])),_:1})])),_:1},8,["show"])],64)):x("",!0)])),_:1})}}},[["__scopeId","data-v-3be70586"]]);export{H as default};
