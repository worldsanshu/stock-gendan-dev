import{V as t,ai as a,x as e,y as s,D as i,H as o,Y as n,A as l,z as r,G as u,F as c,c9 as m,Z as f,E as p,C as d,I as _,J as g,P as v}from"./index-Bbq3x6Nx.js";import{_ as x}from"./uni-nav-bar.Bm9g6lmq.js";import{o as j,r as y}from"./uni-app.es.O2n2Ykid.js";import{_ as b}from"./u-skeleton.QRWuA7w7.js";import{_ as h}from"./u-empty.DWhe21T4.js";import{_ as k}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.Bn6hqqLC.js";import"./uni-status-bar.zUwDwKQb.js";import"./u-icon.D11EDMwi.js";const w=k({__name:"user-center-station-message-notification",setup(k){let w=t(!1);const F=t([]);async function C(){const{data:t}=await m({page:1});t.data.length?(F.value=t.data,w.value=!1):(F.value=[],w.value=!0)}return j((async()=>{await C(),a()})),C(),(t,a)=>{const m=y(e("uni-nav-bar"),x),j=d,k=v,C=y(e("up-skeleton"),b),D=y(e("up-empty"),h);return s(),i(c,null,[o(m,{title:t.$t("page.title.notify"),"status-bar":"","left-icon":"left","background-color":"#F8F8F8",fixed:"",border:!1,onClickLeft:n(f)},null,8,["title","onClickLeft"]),o(C,{rows:"6","rows-height":"80",title:!1,isArticle:!1,loading:!n(w)&&!F.value.length},{default:l((()=>[o(j,{class:"notification-list"},{default:l((()=>[(s(!0),i(c,null,p(F.value,(t=>(s(),r(j,{class:"notification-item",key:t.id},{default:l((()=>[o(j,{class:"notification-item__title"},{default:l((()=>[_(g(t.title),1)])),_:2},1024),o(k,{class:"notification-item__desc",nodes:t.info},null,8,["nodes"]),o(j,{class:"notification-item__date"},{default:l((()=>[_(g(t.create_time),1)])),_:2},1024)])),_:2},1024)))),128))])),_:1})])),_:1},8,["loading"]),n(w)&&!F.value.length?(s(),r(D,{key:0,mode:"data",width:"200","margin-top":"520rpx",icon:"/static/Vertretung/noMoreData@2x.png",text:t.$t("common.text.noData")},null,8,["text"])):u("",!0)],64)}}},[["__scopeId","data-v-c1ce1973"]]);export{w as default};
