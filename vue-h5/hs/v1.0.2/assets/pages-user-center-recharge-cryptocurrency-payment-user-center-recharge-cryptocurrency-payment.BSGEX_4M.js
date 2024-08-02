import{R as e,V as t,U as a,x as r,y as l,z as u,A as o,q as n,C as s,H as i,Y as c,Z as m,bW as d,D as f,I as p,J as g,bI as y,c1 as h,F as v,E as _,G as x,bT as b,c2 as C,c3 as R,N as $}from"./index-C2GIS8XA.js";import{_ as j}from"./uni-nav-bar.Zg1d-M-3.js";import{r as k}from"./uni-app.es.D-mx81Kh.js";import{_ as w}from"./u-input.9_je9h9I.js";import{_ as V}from"./uni-icons.DjBNyZ2z.js";import{_ as T,a as U}from"./uni-list.Dtho4nak.js";import{_ as I,a as A}from"./u-form.D4v-KXPE.js";import{_ as D}from"./u-button.L_8noVhP.js";import{_ as N}from"./u-popup.BN_K5CtD.js";import{d as q,r as E}from"./wallet.B0VaweeW.js";import{_ as F}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-status-bar.BigbQ09m.js";import"./u-icon.DMPu1pkE.js";import"./uni-badge.CHatakbd.js";import"./u-line.BqE5KgPU.js";import"./u-loading-icon.DCmWVxWx.js";import"./u-transition.B3SZ3p_L.js";import"./u-status-bar.D5H95ImY.js";const X=F({__name:"user-center-recharge-cryptocurrency-payment",setup(F){const{t:X}=e(),z=t(),B=a({num:"",txid:""}),H={num:{type:"string",required:!0,message:X("virtualCurrencyRecharge.text.rechargeQuantityErrorTip"),trigger:["blur","change"]},txid:{type:"string",message:X("virtualCurrencyRecharge.text.TXIDAddressErrorTip"),trigger:["blur","change"]}},L=t(!1),Q=t([]),W=t(),Y=t(0),Z=t();async function G(){if(parseFloat(Q.value[Y.value].max_money)<parseFloat(B.num))n({title:"充值金额已超过最大可充值金额"+Q.value[Y.value].max_money+"，请重新输入",icon:"none"});else try{await z.value.validate();const{confirm:e}=await C({title:X("common.text.systemHint"),content:X("virtualCurrencyRecharge.text.rechargeTip")});if(e){const{error:e,message:t,data:a}=await E({type:"currency",payment_id:W.value.id,currency:W.value.name,address:W.value.address,money:B.num*Number(W.value.exchange_rate),num:B.num,txid:B.txid,transfer:"transfer"});if(e)return void(await n({title:t,icon:"none"}));R({id:a.id})}}catch(e){e.length&&await n({title:e[0].message,icon:"none"})}}return async function(){const{error:e,data:t,message:a}=await q();e?await n({title:a,icon:"none"}):(Q.value=t.list,t.list.length&&(W.value=t.list[0],Z.value=t.list[1]))}(),(e,t)=>{const a=k(r("uni-nav-bar"),j),n=k(r("up-input"),w),C=$,R=s,q=k(r("uni-icons"),V),E=k(r("uni-list-item"),T),F=k(r("uni-list"),U),X=k(r("up-form-item"),I),Z=k(r("up-form"),A),J=k(r("up-button"),D),O=k(r("up-popup"),N);return l(),u(R,null,{default:o((()=>[i(a,{title:e.$t("virtualCurrencyRecharge.text.title"),"status-bar":"","background-color":"#ff3c36",color:"#fff","left-icon":"left","right-icon":"more",onClickLeft:c(m),onClickRight:c(d)},null,8,["title","onClickLeft","onClickRight"]),W.value?(l(),f(v,{key:0},[i(R,{class:"page-body"},{default:o((()=>[i(n,{modelValue:B.money,"onUpdate:modelValue":t[0]||(t[0]=e=>B.money=e),placeholder:`${W.value.name} (${W.value.agreement})`,"input-align":"center",disabled:"","disabled-color":"#fff","placeholder-style":"color: '#333'","suffix-icon":"arrow-down",onClick:t[1]||(t[1]=e=>L.value=!0)},null,8,["modelValue","placeholder"]),i(R,{class:"code mt-50"},{default:o((()=>[i(C,{mode:"",onClick:t[2]||(t[2]=e=>{return t=Q.value[Y.value].logo_url,void b({urls:[t]});var t}),src:Q.value[Y.value].logo_url,style:{width:"500rpx",height:"500rpx"}},null,8,["src"])])),_:1}),i(F,{class:"mt-50"},{default:o((()=>[i(E,null,{header:o((()=>[i(R,{class:"font-14"},{default:o((()=>[p(g(e.$t("virtualCurrencyRecharge.text.rechargeAddress")),1)])),_:1})])),body:o((()=>[i(R,{class:"font-12-grey u-flex-fill"},{default:o((()=>[p(g(W.value.address),1)])),_:1})])),footer:o((()=>[i(q,{"custom-prefix":"fc-iconfont",type:"icon-copy",onClick:t[3]||(t[3]=e=>c(y)(W.value.address))})])),_:1}),i(E,null,{header:o((()=>[i(R,{class:"font-14"},{default:o((()=>[p(g(e.$t("virtualCurrencyRecharge.text.minimumDeposit")),1)])),_:1})])),body:o((()=>[i(R,{class:"font-12-grey u-flex-fill"},{default:o((()=>[p(g(W.value.min_money),1)])),_:1})])),_:1}),i(E,null,{header:o((()=>[i(R,{class:"font-14"},{default:o((()=>[p(g(e.$t("virtualCurrencyRecharge.text.exchangeRate")),1)])),_:1})])),body:o((()=>[i(R,{class:"font-12-grey u-flex-fill"},{default:o((()=>[p(g(W.value.exchange_rate),1)])),_:1})])),_:1})])),_:1}),i(R,{class:"info mt-50"},{default:o((()=>[i(R,{class:"mt-10 font-12-grey"},{default:o((()=>[p(g(e.$t("virtualCurrencyRecharge.text.tip1")),1)])),_:1}),i(R,{class:"mt-10 font-12-grey"},{default:o((()=>[p(g(e.$t("virtualCurrencyRecharge.text.tip2")),1)])),_:1}),i(R,{class:"mt-10 font-12-grey"},{default:o((()=>[p(g(e.$t("virtualCurrencyRecharge.text.tip2")),1)])),_:1}),i(R,{class:"mt-10 font-12-grey"},{default:o((()=>[p(g(e.$t("virtualCurrencyRecharge.text.tip3")),1)])),_:1}),i(R,{class:"mt-10 font-12-grey"},{default:o((()=>[p(g(e.$t("virtualCurrencyRecharge.text.tip4")),1)])),_:1})])),_:1}),i(Z,{class:"form mt-50","label-width":"120",labelPosition:"top",model:B,rules:H,ref_key:"formRef",ref:z},{default:o((()=>[i(X,{label:e.$t("virtualCurrencyRecharge.text.rechargeQuantity"),required:"",prop:"num",borderBottom:""},{default:o((()=>[i(n,{type:"number",modelValue:B.num,"onUpdate:modelValue":t[4]||(t[4]=e=>B.num=e),placeholder:H.num.message},h({_:2},[B.num?{name:"suffix",fn:o((()=>[p(g(e.$t("virtualCurrencyRecharge.text.balance"))+g(B.num*Number(W.value.exchange_rate))+" "+g(e.$t("common.text.currencyUnit")),1)])),key:"0"}:void 0]),1032,["modelValue","placeholder"])])),_:1},8,["label"]),i(X,{label:e.$t("virtualCurrencyRecharge.text.TXIDAddress"),prop:"txid",borderBottom:""},{default:o((()=>[i(n,{modelValue:B.txid,"onUpdate:modelValue":t[5]||(t[5]=e=>B.txid=e),placeholder:H.txid.message},null,8,["modelValue","placeholder"])])),_:1},8,["label"])])),_:1},8,["model"]),i(J,{class:"mt-50",color:"rgb(255, 69, 0)",text:"提交",onClick:G})])),_:1}),i(O,{show:L.value,onClose:t[6]||(t[6]=e=>L.value=!1),closeable:"",round:"15rpx"},{default:o((()=>[i(R,{class:"popup-content"},{default:o((()=>[i(R,{class:"font-14-bold text-center"},{default:o((()=>[p(g(e.$t("common.text.submit")),1)])),_:1}),i(F,{class:"mt-30"},{default:o((()=>[(l(!0),f(v,null,_(Q.value,((t,a)=>(l(),u(E,{title:t.name,note:t.agreement,thumb:t.logo_url,"thumb-size":"70rpx",clickable:"",key:t.id,"right-text":`${e.$t("virtualCurrencyRecharge.text.minimumRecharge")}${t.min_money}`,onClick:e=>function(e,t){console.log("看看",t),W.value=e,L.value=!1,Y.value=t}(t,a)},null,8,["title","note","thumb","right-text","onClick"])))),128))])),_:1})])),_:1})])),_:1},8,["show"])],64)):x("",!0)])),_:1})}}},[["__scopeId","data-v-c5254b1e"]]);export{X as default};
