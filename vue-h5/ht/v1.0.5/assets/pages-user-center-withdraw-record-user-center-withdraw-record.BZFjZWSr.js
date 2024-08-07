import{R as t,V as a,aj as s,ae as e,x as l,y as r,D as i,H as o,Y as d,A as u,a9 as n,F as c,C as m,Z as f,b_ as _,ac as p,B as w,E as h,z as y,G as k,ck as x,I as g,J as j,O as b}from"./index-DKPlMZwP.js";import{_ as R}from"./uni-nav-bar.C1JXBbFo.js";import{c as C,d as v,r as $}from"./uni-app.es.DA8hZIlc.js";import{_ as D}from"./u-tabs.BFZVV-gD.js";import{_ as B}from"./u-skeleton.SclRLkQ9.js";import{_ as F}from"./u-empty.DXc2gTHf.js";import{_ as H}from"./u-loadmore.IBxWUiPJ.js";import{n as I}from"./wallet.BuahW5wK.js";import{u as S}from"./use-data-loader.GqxdDcm1.js";import{_ as A}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.BR7w3Tsw.js";import"./uni-status-bar.CtCYtMUj.js";import"./u-icon.Ah-ieCQw.js";import"./u-line.BGXf8ogQ.js";import"./u-loading-icon.B7qGBqey.js";const E=A({__name:"user-center-withdraw-record",setup(A){const{t:E}=t(),J=a(0),L=[{name:E("withdrawalsRecord.text.all")},{name:E("withdrawalsRecord.text.withdrawalSuccessful")},{name:E("withdrawalsRecord.text.withdrawalFailed")}],{list:M,noData:V,refresh:W,loadStatus:z,loading:G,loadMore:N}=S(I,null,"list");return C((async()=>{await W({status:J.value}),s()})),v(N),e(J,(()=>W({status:J.value}))),W(),(t,a)=>{const s=$(l("uni-nav-bar"),R),e=$(l("up-tabs"),D),C=m,v=b,I=$(l("up-skeleton"),B),S=$(l("up-empty"),F),A=$(l("up-loadmore"),H);return r(),i(c,null,[o(s,{title:t.$t("page.title.withdrawalsRecord"),"status-bar":"","background-color":"#ff3c36",color:"#fff","right-icon":"more-filled","left-icon":"left",border:!1,fixed:"",onClickLeft:d(f),onClickRight:d(_)},null,8,["title","onClickLeft","onClickRight"]),o(C,{class:"fixed",style:n({top:d(p).statusBarHeight+44+"px"})},{default:u((()=>[o(e,{list:L,scrollable:!1,current:J.value,onChange:a[0]||(a[0]=t=>J.value=t.index)},null,8,["current"])])),_:1},8,["style"]),o(C,{class:"body"},{default:u((()=>[o(I,{class:w({"pt-30":d(G)}),rows:"7","rows-height":"160rpx",title:!1,isArticle:!1,loading:d(G)},{default:u((()=>[o(C,{class:"list"},{default:u((()=>[(r(!0),i(c,null,h(d(M),(a=>(r(),y(C,{class:"list-item border-bottom",key:a.id,onClick:t=>d(x)({id:a.id})},{default:u((()=>[o(C,{class:"list-item-header"},{default:u((()=>[o(C,{class:"list-item-header__left"},{default:u((()=>[o(v,{class:"font-12"},{default:u((()=>[g(j(t.$t("withdrawalsRecord.text.withdrawalMethods")),1)])),_:1})])),_:1}),0===a.status?(r(),y(C,{key:0,class:"list-item-header__status--primary"},{default:u((()=>[g(j(t.$t("withdrawalsRecord.text.processing")),1)])),_:1})):1===a.status?(r(),y(C,{key:1,class:"list-item-header__status--success"},{default:u((()=>[g(j(t.$t("withdrawalsRecord.text.withdrawalSuccessful")),1)])),_:1})):2===a.status?(r(),y(C,{key:2,class:"list-item-header__status--error"},{default:u((()=>[g(j(t.$t("withdrawalsRecord.text.withdrawalFailed")),1)])),_:1})):3===a.status?(r(),y(C,{key:3,class:"list-item-header__status--primary"},{default:u((()=>[g(j(t.$t("withdrawalsRecord.text.cashWithdrawalRefund")),1)])),_:1})):k("",!0)])),_:2},1024),o(C,{class:"list-item-body mt-20"},{default:u((()=>[o(C,{class:"list-item-content__left font-16-red"},{default:u((()=>[g(j(a.money),1)])),_:2},1024),1===a.status?(r(),y(C,{key:0,class:"list-item-content__left font-16-red"},{default:u((()=>[g("手续费："+j(a.fee),1)])),_:2},1024)):k("",!0),o(C,{class:"list-item-content__right font-12-grey"},{default:u((()=>[g(j(a.create_time),1)])),_:2},1024)])),_:2},1024),o(v,{class:"font-12-grey"},{default:u((()=>[g("银行卡/账号信息："+j(a.bank_text),1)])),_:2},1024)])),_:2},1032,["onClick"])))),128))])),_:1})])),_:1},8,["class","loading"]),d(V)?(r(),y(S,{key:0,mode:"data",icon:"/static/empty/empty-data.png",text:t.$t("common.text.noData")},null,8,["text"])):k("",!0),d(V)||d(G)?k("",!0):(r(),y(A,{key:1,status:d(z),onClick:d(N)},null,8,["status","onClick"]))])),_:1})],64)}}},[["__scopeId","data-v-f2d72792"]]);export{E as default};
