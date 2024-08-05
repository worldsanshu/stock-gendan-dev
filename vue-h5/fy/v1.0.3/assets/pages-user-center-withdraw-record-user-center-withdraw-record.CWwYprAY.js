import{R as t,V as a,ai as s,ae as e,x as l,y as i,D as r,H as o,Y as d,A as u,aa as n,F as c,C as m,Z as f,bY as _,ac as p,B as w,E as h,z as y,G as x,ci as k,I as g,J as b,O as j}from"./index-MDKEdHpS.js";import{_ as R}from"./uni-nav-bar.CUk9QvF6.js";import{o as C,a as v,r as $}from"./uni-app.es.4qAlN9XI.js";import{_ as D}from"./u-tabs.CuPsPwFC.js";import{_ as F}from"./u-skeleton.Dig_86UJ.js";import{_ as S}from"./u-empty.BSZlzoo9.js";import{_ as A}from"./u-loadmore.BcdgRFvY.js";import{n as B}from"./wallet.H9brQlrm.js";import{u as H}from"./use-data-loader.7J4XLOc6.js";import{_ as I}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.DwctlgQK.js";import"./uni-status-bar.DmuBQuz_.js";import"./u-icon.BV8HSO_0.js";import"./u-line.CXnVMx3F.js";import"./u-loading-icon.02MGcHoN.js";const L=I({__name:"user-center-withdraw-record",setup(I){const{t:L}=t(),M=a(0),O=[{name:L("withdrawalsRecord.text.all")},{name:L("withdrawalsRecord.text.withdrawalSuccessful")},{name:L("withdrawalsRecord.text.withdrawalFailed")}],{list:W,noData:Y,refresh:z,loadStatus:E,loading:G,loadMore:J}=H(B,null,"list");return C((async()=>{await z({status:M.value}),s()})),v(J),e(M,(()=>z({status:M.value}))),z(),(t,a)=>{const s=$(l("uni-nav-bar"),R),e=$(l("up-tabs"),D),C=m,v=j,B=$(l("up-skeleton"),F),H=$(l("up-empty"),S),I=$(l("up-loadmore"),A);return i(),r(c,null,[o(s,{title:t.$t("page.title.withdrawalsRecord"),"status-bar":"","background-color":"#ff3c36",color:"#fff","right-icon":"more-filled","left-icon":"left",border:!1,fixed:"",onClickLeft:d(f),onClickRight:d(_)},null,8,["title","onClickLeft","onClickRight"]),o(C,{class:"fixed",style:n({top:d(p).statusBarHeight+44+"px"})},{default:u((()=>[o(e,{list:O,scrollable:!1,current:M.value,onChange:a[0]||(a[0]=t=>M.value=t.index)},null,8,["current"])])),_:1},8,["style"]),o(C,{class:"body"},{default:u((()=>[o(B,{class:w({"pt-30":d(G)}),rows:"7","rows-height":"160rpx",title:!1,isArticle:!1,loading:d(G)},{default:u((()=>[o(C,{class:"list"},{default:u((()=>[(i(!0),r(c,null,h(d(W),(a=>(i(),y(C,{class:"list-item border-bottom",key:a.id,onClick:t=>d(k)({id:a.id})},{default:u((()=>[o(C,{class:"list-item-header"},{default:u((()=>[o(C,{class:"list-item-header__left"},{default:u((()=>[o(v,{class:"font-12"},{default:u((()=>[g(b(t.$t("withdrawalsRecord.text.withdrawalMethods")),1)])),_:1})])),_:1}),0===a.status?(i(),y(C,{key:0,class:"list-item-header__status--primary"},{default:u((()=>[g(b(t.$t("withdrawalsRecord.text.processing")),1)])),_:1})):1===a.status?(i(),y(C,{key:1,class:"list-item-header__status--success"},{default:u((()=>[g(b(t.$t("withdrawalsRecord.text.withdrawalSuccessful")),1)])),_:1})):2===a.status?(i(),y(C,{key:2,class:"list-item-header__status--error"},{default:u((()=>[g(b(t.$t("withdrawalsRecord.text.withdrawalFailed")),1)])),_:1})):3===a.status?(i(),y(C,{key:3,class:"list-item-header__status--primary"},{default:u((()=>[g(b(t.$t("withdrawalsRecord.text.cashWithdrawalRefund")),1)])),_:1})):x("",!0)])),_:2},1024),o(C,{class:"list-item-body mt-20"},{default:u((()=>[o(C,{class:"list-item-content__left font-16-red"},{default:u((()=>[g(b(a.money),1)])),_:2},1024),1===a.status?(i(),y(C,{key:0,class:"list-item-content__left font-16-red"},{default:u((()=>[g("手续费："+b(a.fee),1)])),_:2},1024)):x("",!0),o(C,{class:"list-item-content__right font-12-grey"},{default:u((()=>[g(b(a.create_time),1)])),_:2},1024)])),_:2},1024),o(v,{class:"font-12-grey"},{default:u((()=>[g("银行卡/账号信息："+b(a.bank_text),1)])),_:2},1024)])),_:2},1032,["onClick"])))),128))])),_:1})])),_:1},8,["class","loading"]),d(Y)?(i(),y(H,{key:0,mode:"data",icon:"/static/empty/empty-data.png",text:t.$t("common.text.noData")},null,8,["text"])):x("",!0),d(Y)||d(G)?x("",!0):(i(),y(I,{key:1,status:d(E),onClick:d(J)},null,8,["status","onClick"]))])),_:1})],64)}}},[["__scopeId","data-v-f2d72792"]]);export{L as default};
