import{V as s,ap as a,x as o,y as t,z as e,B as l,C as c,H as r,I as A,K as g,J as d,bY as f,N as u,O as m}from"./index-V1zTiwCe.js";import{_ as i,a as Q}from"./u-row.DFqXIJL-.js";import{r as h}from"./uni-app.es.CQrvLY-P.js";import{_ as n}from"./u-button.B0l9ArWO.js";import{_ as p}from"./uni-icons.DZWFI7QG.js";import{_ as w}from"./u-popup.DhY3lYTv.js";import{f as D}from"./index.C621UsXl.js";import{_ as W}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./u-loading-icon.COOKVkTA.js";import"./u-icon.EexHLkhm.js";import"./u-transition.BCj5qmVK.js";import"./u-status-bar.D4sFBUfg.js";const v=""+new URL("share-poster-bg-CyGUTEFI.png",import.meta.url).href,I=W({__name:"user-center-promotion",setup(W){const I=s(),H=s(),C=s(!1);function G(){C.value=!0,f({data:H.value+I.value,showToast:!0})}function F(){C.value=!1}return a((()=>{D().then((s=>{console.log("loginRes",s.data),I.value=s.data.url,H.value=s.data.share_title}))})),(s,a)=>{const f=u,D=l,W=h(o("up-col"),i),U=h(o("up-row"),Q),P=h(o("up-button"),n),X=m,L=h(o("uni-icons"),p),B=h(o("up-popup"),w);return c(),t(D,{class:"root"},{default:e((()=>[r(f,{class:"bg-image",src:v}),r(D,{class:"card"},{default:e((()=>[r(U,null,{default:e((()=>[r(W,{span:"6"},{default:e((()=>[r(D,{class:"cel border-right"},{default:e((()=>[r(D,{class:"font-14"},{default:e((()=>[A("邀请用户")])),_:1}),r(D,{class:"font-14-red"},{default:e((()=>[A("10人")])),_:1})])),_:1})])),_:1}),r(W,{span:"6"},{default:e((()=>[r(D,{class:"cel"},{default:e((()=>[r(D,{class:"font-14"},{default:e((()=>[A("获得提成")])),_:1}),r(D,{class:"font-14-red"},{default:e((()=>[A("10元")])),_:1})])),_:1})])),_:1})])),_:1}),r(P,{onClick:G,class:"btn mt-40",color:"linear-gradient(to right,#FF4B2B,#FF9C6E)",shape:"circle",text:"邀请好友赚佣金"}),r(P,{class:"btn mt-40",color:"#ff0000",type:"primary",plain:!0,shape:"circle",text:"推广明细"}),r(D,{class:"info mt-40"},{default:e((()=>[r(D,{class:"font-12"},{default:e((()=>[A("通过微信、QQ等其他方式进行推广")])),_:1}),r(D,{class:"font-12 mt-5"},{default:e((()=>[A("通过链接注册的客户属于您的用户")])),_:1}),r(D,{class:"font-12 mt-5"},{default:e((()=>[A("当这些用户在本站点买，您可以赚取服务费")])),_:1})])),_:1})])),_:1}),r(D,{class:"card"},{default:e((()=>[r(D,{class:"font-18-red-bold text-center"},{default:e((()=>[A("推广提成")])),_:1}),r(D,{class:"font-14-grey mt-10"},{default:e((()=>[A(" 新用户通过您的推广链接注册以后，属于您的邀请用户。该用户每发生一笔策略，策略完成后您就会获得交易综合费和续期费用的 "),r(X,{class:"font-14-red"},{default:e((()=>[A("50%")])),_:1}),A(" ， 当您的提成到达一定程度后，您就可以随时取出，也可以用户本站策略。 ")])),_:1}),r(D,{class:"step-group mt-50"},{default:e((()=>[r(f,{class:"step-image",src:"data:image/png;base64,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"}),r(D,{class:"step-arrow"},{default:e((()=>[r(L,{"custom-prefix":"fc-iconfont",type:"icon-step-right",color:"#ccc"})])),_:1}),r(f,{class:"step-image",src:"data:image/png;base64,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"})])),_:1}),r(D,{class:"step-group mt-10"},{default:e((()=>[r(D,{class:"font-12-grey"},{default:e((()=>[A("2.被邀请人发起交易")])),_:1}),r(D,{class:"font-12-grey"},{default:e((()=>[A("3.卖出持仓股票")])),_:1})])),_:1}),r(D,{class:"step-group mt-50"},{default:e((()=>[r(D,{class:"step-group-column"},{default:e((()=>[r(L,{"custom-prefix":"fc-iconfont",type:"icon-step-top",color:"#ccc"}),r(f,{class:"step-image-1 mt-50",src:"data:image/png;base64,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"}),r(D,{class:"font-12-grey mt-10"},{default:e((()=>[A("1.邀请好友注册")])),_:1})])),_:1}),r(D,{class:"step-group-column"},{default:e((()=>[r(L,{class:"rotate-180","custom-prefix":"fc-iconfont",type:"icon-step-top",color:"#ccc"}),r(f,{class:"step-image mt-50",src:"data:image/png;base64,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"}),r(D,{class:"font-12-grey mt-10"},{default:e((()=>[A("4.邀请人获得提成")])),_:1})])),_:1})])),_:1})])),_:1}),r(D,{style:{height:"100rpx"}}),r(B,{show:C.value,onClose:F,onOpen:G,mode:"center",customStyle:"border-radius:20rpx"},{default:e((()=>[r(D,{class:"share-modal"},{default:e((()=>[g("div",{class:"share-modal-tip"},"链接已复制，直接粘贴分享吧"),g("div",{class:"share-modal-title"},d(H.value),1),g("div",{class:"share-modal-url"},d(I.value),1),g("div",{class:"share-modal-btn",onClick:a[0]||(a[0]=s=>C.value=!1)},"点击关闭")])),_:1})])),_:1},8,["show"])])),_:1})}}},[["__scopeId","data-v-23ceb592"]]);export{I as default};
