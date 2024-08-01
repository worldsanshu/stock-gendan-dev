import{V as e,U as a,x as t,y as o,z as l,A as n,C as r,H as s,Y as u,Z as i,I as m,J as c,G as p,c4 as f,D as d,E as _,F as y,q as x,c2 as g,t as b,N as j,O as v}from"./index-Cy3uvPIr.js";import{_ as h}from"./uni-nav-bar.DJERxGo7.js";import{r as w}from"./uni-app.es.CVIHvXyV.js";import{_ as k,a as V}from"./uni-list.CaLGQCbb.js";import{_ as C,a as I}from"./u-radio-group.DzY5wgRf.js";import{_ as T}from"./u-input.Cs8mjgK-.js";import{_ as U,a as $}from"./u-form.OHThwBrU.js";import{_ as q}from"./u-button.D6yQl8EG.js";import{p as F}from"./wallet.BZLU2TTf.js";import{_ as L}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.CZf2lIvA.js";import"./uni-status-bar.BPsLfMgx.js";import"./uni-badge.DQCZUosr.js";import"./u-icon.D8JKd13y.js";import"./u-line.CUxvcpFo.js";import"./u-loading-icon.DrI5BCpq.js";const N=L({__name:"user-center-cbpay",props:{oItem:String},setup(L){const N=e(),O=L;let S=a(JSON.parse(O.oItem)),B=null,J=null,R=e(0);S.extra&&(B=e(S.extra[0].value),J=a(S.extra),A(S.extra[0].value));const Z=a({money:"",payment_id:S.id,extra_value:B||null}),z={money:{type:"string",required:!0,message:"请输入有效的金额!",trigger:["blur","change"]}};async function A(e){for(let a=0;a<=S.extra.length;a++)if(S.extra[a].value===e)return S.min_money=S.extra[a].min,S.max_money=S.extra[a].max,R.value=S.extra[a].multiple,!1}async function D(){try{if(await N.value.validate(),Z.money<parseFloat(S.min_money))return await x({title:`当前金额不能小于${S.min_money}！`,icon:"none"}),!1;if(Z.money>parseFloat(S.max_money))return await x({title:`当前金额不能大于${S.max_money}！`,icon:"none"}),!1;if(S.extra&&Z.money%R.value!=0)return await x({title:`请输入${R.value}的倍数金额！`,icon:"none"}),!1;const e=await F(Z);0===e.error?(window.plus?plus.runtime.openURL(e.data.pay_url,(function(e){x({title:"打开外部浏览器出错: "+e.message,icon:"none"})})):setTimeout((()=>window.open(e.data.pay_url,"_blank"))),g({title:"付款确认",content:"完成付款后，点击「已完成付款」，查看订单支付状态。",confirmText:"已完成付款",cancelText:"更换支付",success:function(e){e.confirm?b({url:"/pages/user-center/user-center"}):e.cancel&&i()}})):g({title:"温馨提示",content:e.message})}catch(e){e.length&&await x({title:e[0].message,icon:"none"})}}return(e,a)=>{const x=w(t("uni-nav-bar"),h),g=j,b=r,F=w(t("uni-list-item"),k),L=v,O=w(t("uni-list"),V),E=w(t("up-radio"),C),G=w(t("up-radio-group"),I),H=w(t("up-input"),T),P=w(t("up-form-item"),U),W=w(t("up-form"),$),Y=w(t("up-button"),q);return o(),l(b,null,{default:n((()=>[s(x,{title:u(S).name,"status-bar":"","background-color":"#ff3c36",color:"#fff","left-icon":"left",onClickLeft:u(i)},null,8,["title","onClickLeft"]),u(S)?(o(),l(b,{key:0,class:"page-body"},{default:n((()=>[s(b,{class:"code mt-50"},{default:n((()=>[s(g,{src:u(S).logo_url,style:{width:"500rpx",height:"500rpx"}},null,8,["src"])])),_:1}),s(O,{class:"mt-50"},{default:n((()=>[s(F,null,{header:n((()=>[s(b,{class:"font-14"},{default:n((()=>[m("最少充值金额：")])),_:1})])),body:n((()=>[s(b,{class:"font-12-grey u-flex-fill"},{default:n((()=>[m(c(u(S).min_money),1)])),_:1})])),_:1}),s(F,null,{header:n((()=>[s(b,{class:"font-14"},{default:n((()=>[m("最大充值金额：")])),_:1})])),body:n((()=>[s(b,{class:"font-12-grey u-flex-fill"},{default:n((()=>[m(c(u(S).max_money),1)])),_:1})])),_:1}),u(S).extra&&u(R)>1?(o(),l(F,{key:0},{header:n((()=>[s(b,{class:"font-14"},{default:n((()=>[m("充值金额为："),s(L,{style:{color:"#3c9cff"}},{default:n((()=>[m(c(u(R)),1)])),_:1}),m(" 的倍数")])),_:1})])),_:1})):p("",!0)])),_:1}),s(W,{class:"form mt-50","label-width":"120",labelPosition:"top",model:Z,rules:z,ref_key:"formRef",ref:N},{default:n((()=>[u(S).extra?(o(),l(G,{key:0,modelValue:u(B),"onUpdate:modelValue":a[0]||(a[0]=e=>f(B)?B.value=e:B=e),class:"up-radio-group",onChange:A},{default:n((()=>[(o(!0),d(y,null,_(u(J),((e,a)=>(o(),l(E,{customStyle:{marginBottom:"8px"},key:a,label:e.title,name:e.value,activeColor:"#19be6b",style:{"margin-right":"40rpx"}},null,8,["label","name"])))),128))])),_:1},8,["modelValue"])):p("",!0),s(P,{label:"充值金额",required:"",prop:"money",borderBottom:""},{default:n((()=>[s(H,{type:"number",modelValue:Z.money,"onUpdate:modelValue":a[1]||(a[1]=e=>Z.money=e),placeholder:"请输入充值金额"},null,8,["modelValue"])])),_:1})])),_:1},8,["model"]),s(Y,{class:"mt-50",color:"rgb(255, 69, 0)",text:"提交",onClick:D})])),_:1})):p("",!0)])),_:1})}}},[["__scopeId","data-v-045a1c13"]]);export{N as default};
