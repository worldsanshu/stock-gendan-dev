import{_ as l}from"./uni-nav-bar.D3FG-aeM.js";import{V as t,U as e,x as a,y as s,z as u,A as n,C as i,H as o,Y as c,Z as r,B as f,I as d,J as m,O as _}from"./index-DxUBkcQO.js";import{r as p}from"./uni-app.es.WyEFFhdr.js";import{_ as b}from"./u-icon.BCEdui8G.js";import{_ as x}from"./u-button.B6sBVj6I.js";import{_ as v}from"./u-picker.DG4giOKa.js";import{_ as j}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.BDom0B1X.js";import"./uni-status-bar.DTIfAn29.js";import"./u-loading-icon.DIzsuW0j.js";import"./u-popup.CuKAp3Fg.js";import"./u-transition.Dd8ALNVU.js";import"./u-status-bar.CvhVCdKp.js";const h=j({__name:"user-center-apply-for-extension",setup(j){const h=t(!1),g=e([[{label:"请选择操盘期限",index:0}]]),k=t("请选择续期期限"),C=t(0);(()=>{for(let l=1;l<=30;l++)g[0].push({label:`${l}天`,index:l})})();const w=()=>{h.value=!0},T=()=>{h.value=!1},y=l=>{k.value=l.value[0].label,C.value=l.value[0].index,T()};return(t,e)=>{const j=p(a("uni-nav-bar"),l),z=i,I=_,$=p(a("up-icon"),b),B=p(a("up-button"),x),H=p(a("up-picker"),v);return s(),u(z,null,{default:n((()=>[o(j,{title:"申请延期","status-bar":"","background-color":"#ff3c36",color:"#fff","left-icon":"left",onClickLeft:c(r)},null,8,["onClickLeft"]),o(z,{class:"page-bg"}),o(z,{class:"main-content"},{default:n((()=>[o(z,{class:"select-box",onClick:w},{default:n((()=>[o(I,{class:f({"no-select-text":0===C.value})},{default:n((()=>[d(m(k.value),1)])),_:1},8,["class"]),o($,{name:"arrow-down-fill",color:"#c2c2c2",size:"12"})])),_:1}),o(z,{class:"main-list"},{default:n((()=>[o(z,{class:"list-item"},{default:n((()=>[o(I,{class:"list-item-left"},{default:n((()=>[d("操盘期限")])),_:1}),o(z,{class:"list-item-right"},{default:n((()=>[o(I,null,{default:n((()=>[d("2024-07-09至2024-07-10")])),_:1})])),_:1})])),_:1}),o(z,{class:"list-item"},{default:n((()=>[o(I,{class:"list-item-left"},{default:n((()=>[d("续期产生利息")])),_:1}),o(z,{class:"list-item-right"},{default:n((()=>[o(I)])),_:1})])),_:1}),o(z,{class:"list-item"},{default:n((()=>[o(I,{class:"list-item-left"},{default:n((()=>[d("账户余额")])),_:1}),o(z,{class:"list-item-right"},{default:n((()=>[o(I,null,{default:n((()=>[d("2024-07-09至2024-07-10")])),_:1})])),_:1})])),_:1})])),_:1}),o(B,{color:"#ff4500",text:"提交续期申请"}),o(z,{class:"record-box"},{default:n((()=>[o(z,{class:"record-header"},{default:n((()=>[o(I,null,{default:n((()=>[d("申请延期记录")])),_:1}),o($,{name:"reload",color:"#252525",size:"20"})])),_:1}),o(z,{class:"record-content-header"},{default:n((()=>[o(I,null,{default:n((()=>[d("延期期限")])),_:1}),o(I,null,{default:n((()=>[d("延期总利息")])),_:1}),o(I,null,{default:n((()=>[d("申请时间")])),_:1}),o(I,null,{default:n((()=>[d("申请状态")])),_:1})])),_:1}),o(z,{class:"record-list-content"},{default:n((()=>[o(z,{class:"list-main-content"},{default:n((()=>[o(z,{class:"list-item"},{default:n((()=>[o(z,{class:"list-item-inner"},{default:n((()=>[o(I,null,{default:n((()=>[d("中国石化")])),_:1}),o(I,null,{default:n((()=>[d("6.44")])),_:1})])),_:1}),o(z,{class:"list-item-inner"},{default:n((()=>[o(I,null,{default:n((()=>[d("100.00")])),_:1}),o(I,null,{default:n((()=>[d("6.44")])),_:1})])),_:1}),o(z,{class:"list-item-inner"},{default:n((()=>[o(I,null,{default:n((()=>[d("2024-07-10")])),_:1}),o(I,null,{default:n((()=>[d("14:15:48")])),_:1})])),_:1}),o(z,{class:"list-item-inner"},{default:n((()=>[o(I,null,{default:n((()=>[d("2024-07-10")])),_:1}),o(I,null,{default:n((()=>[d("14:15:48")])),_:1})])),_:1})])),_:1})])),_:1})])),_:1})])),_:1})])),_:1}),o(H,{show:h.value,cancelText:t.$t("common.text.cancel"),confirmText:t.$t("common.text.confirm"),onCancel:T,onConfirm:y,columns:g,keyName:"label"},null,8,["show","cancelText","confirmText","columns"])])),_:1})}}},[["__scopeId","data-v-b3a7584b"]]);export{h as default};
