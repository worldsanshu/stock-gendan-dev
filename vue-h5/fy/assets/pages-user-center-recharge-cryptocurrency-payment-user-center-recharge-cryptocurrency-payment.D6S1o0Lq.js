import{R as e,V as t,U as a,x as r,y as l,z as u,A as o,q as n,C as s,H as i,Y as c,Z as m,bX as d,D as f,I as p,J as g,bJ as y,c2 as h,F as v,E as _,G as x,bU as b,c3 as C,c4 as R,N as $}from"./index-CZnQGPlz.js";import{_ as j}from"./uni-nav-bar.CFUrwkPX.js";import{r as k}from"./uni-app.es.SncV18af.js";import{_ as w}from"./u-input.CAQaKl7I.js";import{_ as V}from"./uni-icons.CvxGfOVM.js";import{_ as U,a as T}from"./uni-list.CVAZRZP8.js";import{_ as A,a as D}from"./u-form.h6teW9Z6.js";import{_ as I}from"./u-button.Vk-ypJwg.js";import{_ as N}from"./u-popup.BLupctrx.js";import{d as X,r as q}from"./wallet.DIO-du5A.js";import{_ as E}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-status-bar.BWaKZAxB.js";import"./u-icon.BwGcVl8G.js";import"./uni-badge.BXxoVFFi.js";import"./u-line.BbGocjR1.js";import"./u-loading-icon.CiWHMAt-.js";import"./u-transition.CG8TwiL0.js";import"./u-status-bar.CWglgKBP.js";const F=E({__name:"user-center-recharge-cryptocurrency-payment",setup(E){const{t:F}=e(),z=t(),B=a({num:"",txid:""}),H={num:{type:"string",required:!0,message:F("virtualCurrencyRecharge.text.rechargeQuantityErrorTip"),trigger:["blur","change"]},txid:{type:"string",message:F("virtualCurrencyRecharge.text.TXIDAddressErrorTip"),trigger:["blur","change"]}},J=t(!1),L=t([]),Q=t(),Y=t(0),Z=t();async function G(){if(parseFloat(L.value[Y.value].max_money)<parseFloat(B.num))n({title:"充值金额已超过最大可充值金额"+L.value[Y.value].max_money+"，请重新输入",icon:"none"});else try{await z.value.validate();const{confirm:e}=await C({title:F("common.text.systemHint"),content:F("virtualCurrencyRecharge.text.rechargeTip")});if(e){const{error:e,message:t,data:a}=await q({type:"currency",payment_id:Q.value.id,currency:Q.value.name,address:Q.value.address,money:B.num*Number(Q.value.exchange_rate),num:B.num,txid:B.txid,transfer:"transfer"});if(e)return void(await n({title:t,icon:"none"}));R({id:a.id})}}catch(e){e.length&&await n({title:e[0].message,icon:"none"})}}return async function(){const{error:e,data:t,message:a}=await X();e?await n({title:a,icon:"none"}):(L.value=t.list,t.list.length&&(Q.value=t.list[0],Z.value=t.list[1]))}(),(e,t)=>{const a=k(r("uni-nav-bar"),j),n=k(r("up-input"),w),C=$,R=s,X=k(r("uni-icons"),V),q=k(r("uni-list-item"),U),E=k(r("uni-list"),T),F=k(r("up-form-item"),A),Z=k(r("up-form"),D),O=k(r("up-button"),I),P=k(r("up-popup"),N);return l(),u(R,null,{default:o((()=>[i(a,{title:e.$t("virtualCurrencyRecharge.text.title"),"status-bar":"","background-color":"#ff3c36",color:"#fff","left-icon":"left","right-icon":"more",onClickLeft:c(m),onClickRight:c(d)},null,8,["title","onClickLeft","onClickRight"]),Q.value?(l(),f(v,{key:0},[i(R,{class:"page-body"},{default:o((()=>[i(n,{modelValue:B.money,"onUpdate:modelValue":t[0]||(t[0]=e=>B.money=e),placeholder:`${Q.value.name} (${Q.value.agreement})`,"input-align":"center",disabled:"","disabled-color":"#fff","placeholder-style":"color: '#333'","suffix-icon":"arrow-down",onClick:t[1]||(t[1]=e=>J.value=!0)},null,8,["modelValue","placeholder"]),i(R,{class:"code mt-50"},{default:o((()=>[i(C,{mode:"",onClick:t[2]||(t[2]=e=>{return t=L.value[Y.value].logo_url,void b({urls:[t]});var t}),src:L.value[Y.value].logo_url,style:{width:"500rpx",height:"500rpx"}},null,8,["src"])])),_:1}),i(E,{class:"mt-50"},{default:o((()=>[i(q,null,{header:o((()=>[i(R,{class:"font-14"},{default:o((()=>[p(g(e.$t("virtualCurrencyRecharge.text.rechargeAddress")),1)])),_:1})])),body:o((()=>[i(R,{class:"font-12-grey u-flex-fill"},{default:o((()=>[p(g(Q.value.address),1)])),_:1})])),footer:o((()=>[i(X,{"custom-prefix":"fc-iconfont",type:"icon-copy",onClick:t[3]||(t[3]=e=>c(y)(Q.value.address))})])),_:1}),i(q,null,{header:o((()=>[i(R,{class:"font-14"},{default:o((()=>[p(g(e.$t("virtualCurrencyRecharge.text.minimumDeposit")),1)])),_:1})])),body:o((()=>[i(R,{class:"font-12-grey u-flex-fill"},{default:o((()=>[p(g(Q.value.min_money),1)])),_:1})])),_:1}),i(q,null,{header:o((()=>[i(R,{class:"font-14"},{default:o((()=>[p(g(e.$t("virtualCurrencyRecharge.text.exchangeRate")),1)])),_:1})])),body:o((()=>[i(R,{class:"font-12-grey u-flex-fill"},{default:o((()=>[p(g(Q.value.exchange_rate),1)])),_:1})])),_:1})])),_:1}),i(R,{class:"info mt-50"},{default:o((()=>[i(R,{class:"mt-10 font-12-grey"},{default:o((()=>[p(g(e.$t("virtualCurrencyRecharge.text.tip1")),1)])),_:1}),i(R,{class:"mt-10 font-12-grey"},{default:o((()=>[p(g(e.$t("virtualCurrencyRecharge.text.tip2")),1)])),_:1}),i(R,{class:"mt-10 font-12-grey"},{default:o((()=>[p(g(e.$t("virtualCurrencyRecharge.text.tip2")),1)])),_:1}),i(R,{class:"mt-10 font-12-grey"},{default:o((()=>[p(g(e.$t("virtualCurrencyRecharge.text.tip3")),1)])),_:1}),i(R,{class:"mt-10 font-12-grey"},{default:o((()=>[p(g(e.$t("virtualCurrencyRecharge.text.tip4")),1)])),_:1})])),_:1}),i(Z,{class:"form mt-50","label-width":"120",labelPosition:"top",model:B,rules:H,ref_key:"formRef",ref:z},{default:o((()=>[i(F,{label:e.$t("virtualCurrencyRecharge.text.rechargeQuantity"),required:"",prop:"num",borderBottom:""},{default:o((()=>[i(n,{type:"number",modelValue:B.num,"onUpdate:modelValue":t[4]||(t[4]=e=>B.num=e),placeholder:H.num.message},h({_:2},[B.num?{name:"suffix",fn:o((()=>[p(g(e.$t("virtualCurrencyRecharge.text.balance"))+g(B.num*Number(Q.value.exchange_rate))+" "+g(e.$t("common.text.currencyUnit")),1)])),key:"0"}:void 0]),1032,["modelValue","placeholder"])])),_:1},8,["label"]),i(F,{label:e.$t("virtualCurrencyRecharge.text.TXIDAddress"),prop:"txid",borderBottom:""},{default:o((()=>[i(n,{modelValue:B.txid,"onUpdate:modelValue":t[5]||(t[5]=e=>B.txid=e),placeholder:H.txid.message},null,8,["modelValue","placeholder"])])),_:1},8,["label"])])),_:1},8,["model"]),i(O,{class:"mt-50",color:"rgb(255, 69, 0)",text:"提交",onClick:G})])),_:1}),i(P,{show:J.value,onClose:t[6]||(t[6]=e=>J.value=!1),closeable:"",round:"15rpx"},{default:o((()=>[i(R,{class:"popup-content"},{default:o((()=>[i(R,{class:"font-14-bold text-center"},{default:o((()=>[p(g(e.$t("common.text.submit")),1)])),_:1}),i(E,{class:"mt-30"},{default:o((()=>[(l(!0),f(v,null,_(L.value,((t,a)=>(l(),u(q,{title:t.name,note:t.agreement,thumb:t.logo_url,"thumb-size":"70rpx",clickable:"",key:t.id,"right-text":`${e.$t("virtualCurrencyRecharge.text.minimumRecharge")}${t.min_money}`,onClick:e=>function(e,t){console.log("看看",t),Q.value=e,J.value=!1,Y.value=t}(t,a)},null,8,["title","note","thumb","right-text","onClick"])))),128))])),_:1})])),_:1})])),_:1},8,["show"])],64)):x("",!0)])),_:1})}}},[["__scopeId","data-v-7e6517e1"]]);export{F as default};
