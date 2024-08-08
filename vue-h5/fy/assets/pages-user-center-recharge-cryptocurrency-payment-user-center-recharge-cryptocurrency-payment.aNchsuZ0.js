import{R as e,V as t,U as a,x as r,y as l,z as u,v as o,B as n,C as s,H as i,Y as c,Z as m,bZ as d,D as f,I as p,J as g,aX as y,c4 as v,F as h,E as _,G as x,bX as b,c5 as C,c6 as R,N as $}from"./index-V1zTiwCe.js";import{_ as j}from"./uni-nav-bar.B9quvnCD.js";import{r as k}from"./uni-app.es.CQrvLY-P.js";import{_ as w}from"./u-input.CiR-oGCa.js";import{_ as V}from"./uni-icons.DZWFI7QG.js";import{_ as D,a as I}from"./uni-list.D1tQvdTn.js";import{_ as T,a as U}from"./u-form.6y5zeQu3.js";import{_ as B}from"./u-button.B0l9ArWO.js";import{_ as E}from"./u-popup.DhY3lYTv.js";import{d as F,r as X}from"./wallet.STqXUiio.js";import{_ as A}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-status-bar.BjedvyN3.js";import"./u-icon.EexHLkhm.js";import"./uni-badge.BTiAu34O.js";import"./u-line.BcX0DQci.js";import"./u-loading-icon.COOKVkTA.js";import"./u-transition.BCj5qmVK.js";import"./u-status-bar.D4sFBUfg.js";const H=A({__name:"user-center-recharge-cryptocurrency-payment",setup(A){const{t:H}=e(),N=t(),Q=a({num:"",txid:""}),Z={num:{type:"string",required:!0,message:H("virtualCurrencyRecharge.text.rechargeQuantityErrorTip"),trigger:["blur","change"]},txid:{type:"string",message:H("virtualCurrencyRecharge.text.TXIDAddressErrorTip"),trigger:["blur","change"]}},q=t(!1),z=t([]),G=t(),J=t(0),L=t();async function Y(){if(parseFloat(z.value[J.value].max_money)<parseFloat(Q.num))o({title:"充值金额已超过最大可充值金额"+z.value[J.value].max_money+"，请重新输入",icon:"none"});else try{await N.value.validate();const{confirm:e}=await C({title:H("common.text.systemHint"),content:H("virtualCurrencyRecharge.text.rechargeTip")});if(e){const{error:e,message:t,data:a}=await X({type:"currency",payment_id:G.value.id,currency:G.value.name,address:G.value.address,money:Q.num*Number(G.value.exchange_rate),num:Q.num,txid:Q.txid,transfer:"transfer"});if(e)return void(await o({title:t,icon:"none"}));R({id:a.id})}}catch(e){e.length&&await o({title:e[0].message,icon:"none"})}}return async function(){const{error:e,data:t,message:a}=await F();e?await o({title:a,icon:"none"}):(z.value=t.list,t.list.length&&(G.value=t.list[0],L.value=t.list[1]))}(),(e,t)=>{const a=k(r("uni-nav-bar"),j),o=k(r("up-input"),w),C=$,R=n,F=k(r("uni-icons"),V),X=k(r("uni-list-item"),D),A=k(r("uni-list"),I),H=k(r("up-form-item"),T),L=k(r("up-form"),U),P=k(r("up-button"),B),K=k(r("up-popup"),E);return s(),l(R,null,{default:u((()=>[i(a,{title:e.$t("virtualCurrencyRecharge.text.title"),"status-bar":"","background-color":"#ff3c36",color:"#fff",fixed:"","left-icon":"left","right-icon":"more",onClickLeft:c(m),onClickRight:c(d)},null,8,["title","onClickLeft","onClickRight"]),G.value?(s(),f(h,{key:0},[i(R,{class:"page-body"},{default:u((()=>[i(o,{modelValue:Q.money,"onUpdate:modelValue":t[0]||(t[0]=e=>Q.money=e),placeholder:`${G.value.name} (${G.value.agreement})`,"input-align":"center",disabled:"","disabled-color":"#fff","placeholder-style":"color: '#333'","suffix-icon":"arrow-down",onClick:t[1]||(t[1]=e=>q.value=!0)},null,8,["modelValue","placeholder"]),i(R,{class:"code mt-50"},{default:u((()=>[i(C,{mode:"",onClick:t[2]||(t[2]=e=>{return t=z.value[J.value].logo_url,void b({urls:[t]});var t}),src:z.value[J.value].logo_url,style:{width:"500rpx",height:"500rpx"}},null,8,["src"])])),_:1}),i(A,{class:"mt-50"},{default:u((()=>[i(X,null,{header:u((()=>[i(R,{class:"font-14"},{default:u((()=>[p(g(e.$t("virtualCurrencyRecharge.text.rechargeAddress")),1)])),_:1})])),body:u((()=>[i(R,{class:"font-12-grey u-flex-fill"},{default:u((()=>[p(g(G.value.address),1)])),_:1})])),footer:u((()=>[i(F,{"custom-prefix":"fc-iconfont",type:"icon-copy",onClick:t[3]||(t[3]=e=>c(y)(G.value.address))})])),_:1}),i(X,null,{header:u((()=>[i(R,{class:"font-14"},{default:u((()=>[p(g(e.$t("virtualCurrencyRecharge.text.minimumDeposit")),1)])),_:1})])),body:u((()=>[i(R,{class:"font-12-grey u-flex-fill"},{default:u((()=>[p(g(G.value.min_money),1)])),_:1})])),_:1}),i(X,null,{header:u((()=>[i(R,{class:"font-14"},{default:u((()=>[p(g(e.$t("virtualCurrencyRecharge.text.exchangeRate")),1)])),_:1})])),body:u((()=>[i(R,{class:"font-12-grey u-flex-fill"},{default:u((()=>[p(g(G.value.exchange_rate),1)])),_:1})])),_:1})])),_:1}),i(R,{class:"info mt-50"},{default:u((()=>[i(R,{class:"mt-10 font-12-grey"},{default:u((()=>[p(g(e.$t("virtualCurrencyRecharge.text.tip1")),1)])),_:1}),i(R,{class:"mt-10 font-12-grey"},{default:u((()=>[p(g(e.$t("virtualCurrencyRecharge.text.tip2")),1)])),_:1}),i(R,{class:"mt-10 font-12-grey"},{default:u((()=>[p(g(e.$t("virtualCurrencyRecharge.text.tip2")),1)])),_:1}),i(R,{class:"mt-10 font-12-grey"},{default:u((()=>[p(g(e.$t("virtualCurrencyRecharge.text.tip3")),1)])),_:1}),i(R,{class:"mt-10 font-12-grey"},{default:u((()=>[p(g(e.$t("virtualCurrencyRecharge.text.tip4")),1)])),_:1})])),_:1}),i(L,{class:"form mt-50","label-width":"120",labelPosition:"top",model:Q,rules:Z,ref_key:"formRef",ref:N},{default:u((()=>[i(H,{label:e.$t("virtualCurrencyRecharge.text.rechargeQuantity"),required:"",prop:"num",borderBottom:""},{default:u((()=>[i(o,{type:"number",modelValue:Q.num,"onUpdate:modelValue":t[4]||(t[4]=e=>Q.num=e),placeholder:Z.num.message},v({_:2},[Q.num?{name:"suffix",fn:u((()=>[p(g(e.$t("virtualCurrencyRecharge.text.balance"))+g(Q.num*Number(G.value.exchange_rate))+" "+g(e.$t("common.text.currencyUnit")),1)])),key:"0"}:void 0]),1032,["modelValue","placeholder"])])),_:1},8,["label"]),i(H,{label:e.$t("virtualCurrencyRecharge.text.TXIDAddress"),prop:"txid",borderBottom:""},{default:u((()=>[i(o,{modelValue:Q.txid,"onUpdate:modelValue":t[5]||(t[5]=e=>Q.txid=e),placeholder:Z.txid.message},null,8,["modelValue","placeholder"])])),_:1},8,["label"])])),_:1},8,["model"]),i(P,{class:"mt-50",color:"rgb(255, 69, 0)",text:"提交",onClick:Y})])),_:1}),i(K,{show:q.value,onClose:t[6]||(t[6]=e=>q.value=!1),closeable:"",round:"15rpx"},{default:u((()=>[i(R,{class:"popup-content"},{default:u((()=>[i(R,{class:"font-14-bold text-center"},{default:u((()=>[p(g(e.$t("common.text.submit")),1)])),_:1}),i(A,{class:"mt-30"},{default:u((()=>[(s(!0),f(h,null,_(z.value,((t,a)=>(s(),l(X,{title:t.name,note:t.agreement,thumb:t.logo_url,"thumb-size":"70rpx",clickable:"",key:t.id,"right-text":`${e.$t("virtualCurrencyRecharge.text.minimumRecharge")}${t.min_money}`,onClick:e=>function(e,t){console.log("看看",t),G.value=e,q.value=!1,J.value=t}(t,a)},null,8,["title","note","thumb","right-text","onClick"])))),128))])),_:1})])),_:1})])),_:1},8,["show"])],64)):x("",!0)])),_:1})}}},[["__scopeId","data-v-3be70586"]]);export{H as default};
