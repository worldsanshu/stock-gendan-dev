import{R as t,V as a,aj as s,ae as e,x as l,D as r,H as i,Y as o,z as d,a9 as u,F as n,B as c,C as m,Z as f,b_ as _,ac as p,A as w,E as h,y,G as k,ck as x,I as g,J as j,O as b}from"./index-DugkB_-4.js";import{_ as R}from"./uni-nav-bar.DGUi_IvM.js";import{c as C,d as v,r as $}from"./uni-app.es.CX-CC-lz.js";import{_ as D}from"./u-tabs.DCj_ugoh.js";import{_ as B}from"./u-skeleton.BYaqrno_.js";import{_ as F}from"./u-empty.CQpJXShg.js";import{_ as H}from"./u-loadmore.DoeN8jY1.js";import{n as I}from"./wallet.BMBhKyJb.js";import{u as S}from"./use-data-loader.C761N3Vm.js";import{_ as A}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.D9BxN9k6.js";import"./uni-status-bar.D_XiF9iL.js";import"./u-icon.Ca1c56qc.js";import"./u-line.BpwwmqXn.js";import"./u-loading-icon.CCS9DSWM.js";const E=A({__name:"user-center-withdraw-record",setup(A){const{t:E}=t(),J=a(0),L=[{name:E("withdrawalsRecord.text.all")},{name:E("withdrawalsRecord.text.withdrawalSuccessful")},{name:E("withdrawalsRecord.text.withdrawalFailed")}],{list:M,noData:V,refresh:W,loadStatus:z,loading:G,loadMore:N}=S(I,null,"list");return C((async()=>{await W({status:J.value}),s()})),v(N),e(J,(()=>W({status:J.value}))),W(),(t,a)=>{const s=$(l("uni-nav-bar"),R),e=$(l("up-tabs"),D),C=c,v=b,I=$(l("up-skeleton"),B),S=$(l("up-empty"),F),A=$(l("up-loadmore"),H);return m(),r(n,null,[i(s,{title:t.$t("page.title.withdrawalsRecord"),"status-bar":"","background-color":"#ff3c36",color:"#fff","right-icon":"more-filled","left-icon":"left",border:!1,fixed:"",onClickLeft:o(f),onClickRight:o(_)},null,8,["title","onClickLeft","onClickRight"]),i(C,{class:"fixed",style:u({top:o(p).statusBarHeight+44+"px"})},{default:d((()=>[i(e,{list:L,scrollable:!1,current:J.value,onChange:a[0]||(a[0]=t=>J.value=t.index)},null,8,["current"])])),_:1},8,["style"]),i(C,{class:"body"},{default:d((()=>[i(I,{class:w({"pt-30":o(G)}),rows:"7","rows-height":"160rpx",title:!1,isArticle:!1,loading:o(G)},{default:d((()=>[i(C,{class:"list"},{default:d((()=>[(m(!0),r(n,null,h(o(M),(a=>(m(),y(C,{class:"list-item border-bottom",key:a.id,onClick:t=>o(x)({id:a.id})},{default:d((()=>[i(C,{class:"list-item-header"},{default:d((()=>[i(C,{class:"list-item-header__left"},{default:d((()=>[i(v,{class:"font-12"},{default:d((()=>[g(j(t.$t("withdrawalsRecord.text.withdrawalMethods")),1)])),_:1})])),_:1}),0===a.status?(m(),y(C,{key:0,class:"list-item-header__status--primary"},{default:d((()=>[g(j(t.$t("withdrawalsRecord.text.processing")),1)])),_:1})):1===a.status?(m(),y(C,{key:1,class:"list-item-header__status--success"},{default:d((()=>[g(j(t.$t("withdrawalsRecord.text.withdrawalSuccessful")),1)])),_:1})):2===a.status?(m(),y(C,{key:2,class:"list-item-header__status--error"},{default:d((()=>[g(j(t.$t("withdrawalsRecord.text.withdrawalFailed")),1)])),_:1})):3===a.status?(m(),y(C,{key:3,class:"list-item-header__status--primary"},{default:d((()=>[g(j(t.$t("withdrawalsRecord.text.cashWithdrawalRefund")),1)])),_:1})):k("",!0)])),_:2},1024),i(C,{class:"list-item-body mt-20"},{default:d((()=>[i(C,{class:"list-item-content__left font-16-red"},{default:d((()=>[g(j(a.money),1)])),_:2},1024),1===a.status?(m(),y(C,{key:0,class:"list-item-content__left font-16-red"},{default:d((()=>[g("手续费："+j(a.fee),1)])),_:2},1024)):k("",!0),i(C,{class:"list-item-content__right font-12-grey"},{default:d((()=>[g(j(a.create_time),1)])),_:2},1024)])),_:2},1024),i(v,{class:"font-12-grey"},{default:d((()=>[g("银行卡/账号信息："+j(a.bank_text),1)])),_:2},1024)])),_:2},1032,["onClick"])))),128))])),_:1})])),_:1},8,["class","loading"]),o(V)?(m(),y(S,{key:0,mode:"data",icon:"/static/empty/empty-data.png",text:t.$t("common.text.noData")},null,8,["text"])):k("",!0),o(V)||o(G)?k("",!0):(m(),y(A,{key:1,status:o(z),onClick:o(N)},null,8,["status","onClick"]))])),_:1})],64)}}},[["__scopeId","data-v-f2d72792"]]);export{E as default};
