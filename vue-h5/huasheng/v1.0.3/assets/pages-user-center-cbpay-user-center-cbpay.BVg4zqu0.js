import{V as e,U as a,x as t,y as o,z as l,A as n,C as r,H as s,Y as i,Z as u,I as m,J as c,G as f,c4 as p,D as d,E as _,F as y,q as x,c2 as g,t as b,N as v,O as h}from"./index-BSZMSohE.js";import{_ as j}from"./uni-nav-bar.DaV7kknO.js";import{c as w,r as k}from"./uni-app.es.xTxZrAO-.js";import{_ as V,a as C}from"./uni-list.BAmADFzC.js";import{_ as U,a as I}from"./u-radio-group.B5Z3Ufqw.js";import{_ as $}from"./u-input.Cv139uxi.js";import{_ as q,a as F}from"./u-form.BetUNbkv.js";import{_ as L}from"./u-button.Bc9J1urq.js";import{p as O}from"./wallet.BMFo6IIZ.js";import{_ as S}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.UvJfiB1T.js";import"./uni-status-bar.DM8Vigxm.js";import"./uni-badge.D3JSGdBF.js";import"./u-icon.rSo6jUIp.js";import"./u-line.Bg8FUxRG.js";import"./u-loading-icon.oQN9K4Zk.js";const B=S({__name:"user-center-cbpay",props:{oItem:String},setup(S){const B=e(),J=S;let N=a(JSON.parse(J.oItem)),P=null,R=null,T=e(0);N.extra&&(P=e(N.extra[0].value),R=a(N.extra),D(N.extra[0].value));const z=a({money:"",payment_id:N.id,extra_value:P||null}),A={money:{type:"string",required:!0,message:"请输入有效的金额!",trigger:["blur","change"]}};async function D(e){for(let a=0;a<=N.extra.length;a++)if(N.extra[a].value===e)return N.min_money=N.extra[a].min,N.max_money=N.extra[a].max,T.value=N.extra[a].multiple,!1}let E=!1;async function G(){try{if(await B.value.validate(),z.money<parseFloat(N.min_money))return await x({title:`当前金额不能小于${N.min_money}！`,icon:"none"}),!1;if(z.money>parseFloat(N.max_money))return await x({title:`当前金额不能大于${N.max_money}！`,icon:"none"}),!1;if(N.extra&&z.money%T.value!=0)return await x({title:`请输入${T.value}的倍数金额！`,icon:"none"}),!1;const e=await O(z);if(e.error)return void(await x({title:e.message}));window.plus?plus.runtime.openURL(e.data.pay_url,(function(e){x({title:"打开外部浏览器出错: "+e.message,icon:"none"})})):g({title:"温馨提示",content:"即将跳转到第三方钱包支付",success:a=>{a.confirm&&(E=!0,window.open(e.data.pay_url))}})}catch(e){e.length&&await x({title:e[0].message,icon:"none"})}}return w((()=>{E&&(E=!1,g({title:"付款确认",content:"完成付款后，点击「已完成付款」，查看订单支付状态。",confirmText:"已完成付款",cancelText:"更换支付",success:function(e){e.confirm?b({url:"/pages/user-center/user-center"}):e.cancel&&u()}}))})),(e,a)=>{const x=k(t("uni-nav-bar"),j),g=v,b=r,w=k(t("uni-list-item"),V),O=h,S=k(t("uni-list"),C),J=k(t("up-radio"),U),E=k(t("up-radio-group"),I),H=k(t("up-input"),$),W=k(t("up-form-item"),q),X=k(t("up-form"),F),Y=k(t("up-button"),L);return o(),l(b,null,{default:n((()=>[s(x,{title:i(N).name,"status-bar":"","background-color":"#ff3c36",color:"#fff","left-icon":"left",onClickLeft:i(u)},null,8,["title","onClickLeft"]),i(N)?(o(),l(b,{key:0,class:"page-body"},{default:n((()=>[s(b,{class:"code mt-50"},{default:n((()=>[s(g,{src:i(N).logo_url,style:{width:"500rpx",height:"500rpx"}},null,8,["src"])])),_:1}),s(S,{class:"mt-50"},{default:n((()=>[s(w,null,{header:n((()=>[s(b,{class:"font-14"},{default:n((()=>[m("最少充值金额：")])),_:1})])),body:n((()=>[s(b,{class:"font-12-grey u-flex-fill"},{default:n((()=>[m(c(i(N).min_money),1)])),_:1})])),_:1}),s(w,null,{header:n((()=>[s(b,{class:"font-14"},{default:n((()=>[m("最大充值金额：")])),_:1})])),body:n((()=>[s(b,{class:"font-12-grey u-flex-fill"},{default:n((()=>[m(c(i(N).max_money),1)])),_:1})])),_:1}),i(N).extra&&i(T)>1?(o(),l(w,{key:0},{header:n((()=>[s(b,{class:"font-14"},{default:n((()=>[m("充值金额为："),s(O,{style:{color:"#3c9cff"}},{default:n((()=>[m(c(i(T)),1)])),_:1}),m(" 的倍数")])),_:1})])),_:1})):f("",!0)])),_:1}),s(X,{class:"form mt-50","label-width":"120",labelPosition:"top",model:z,rules:A,ref_key:"formRef",ref:B},{default:n((()=>[i(N).extra?(o(),l(E,{key:0,modelValue:i(P),"onUpdate:modelValue":a[0]||(a[0]=e=>p(P)?P.value=e:P=e),class:"up-radio-group",onChange:D},{default:n((()=>[(o(!0),d(y,null,_(i(R),((e,a)=>(o(),l(J,{customStyle:{marginBottom:"8px"},key:a,label:e.title,name:e.value,activeColor:"#19be6b",style:{"margin-right":"40rpx"}},null,8,["label","name"])))),128))])),_:1},8,["modelValue"])):f("",!0),s(W,{label:"充值金额",required:"",prop:"money",borderBottom:""},{default:n((()=>[s(H,{type:"number",modelValue:z.money,"onUpdate:modelValue":a[1]||(a[1]=e=>z.money=e),placeholder:"请输入充值金额"},null,8,["modelValue"])])),_:1})])),_:1},8,["model"]),s(Y,{class:"mt-50",color:"rgb(255, 69, 0)",text:"提交",onClick:G})])),_:1})):f("",!0)])),_:1})}}},[["__scopeId","data-v-b8742ca3"]]);export{B as default};
