import{ai as t,x as s,y as a,D as o,H as r,Y as i,z as e,G as n,A as l,F as u,aq as p,C as m,Z as d}from"./index-BlcziP-N.js";import{_ as c}from"./uni-nav-bar.BDffF3B1.js";import{o as f,a as j,r as g}from"./uni-app.es.Cz3hYFyc.js";import{_}from"./u-tabs.DtlQ8f4r.js";import{u as b,_ as y}from"./use-star-investor.CSbo9tWz.js";import{_ as k}from"./u-skeleton.DdsaugOf.js";import{_ as v}from"./u-empty.Cfwa8Bo2.js";import{_ as h}from"./u-loadmore.lpWq-G6K.js";import{O as x}from"./index.DR3NTLol.js";import{_ as C}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.gwpSiTWR.js";import"./uni-status-bar.DG1EltFF.js";import"./u-avatar.KJIXuSi6.js";import"./u-icon.O0u5a7Qd.js";import"./u-text.B8mhS4oP.js";import"./u-link.RTVtr5FV.js";import"./u-tag.DkDFpDd5.js";import"./u-transition.BR_pztAh.js";import"./use-data-loader.L799C17X.js";import"./u-line.CDMJfdbj.js";import"./u-loading-icon.SDEVw8x5.js";const w=C({__name:"star-investor-v2-copy",props:{type:String},setup(C){let w=0;const S=C,{tabs:A,tabCurrent:D,list:L,loading:N,loadMore:O,loadStatus:Z,noData:q,refresh:z}=b(Number(S.type));function B(){p("/pages/record/record",{id:20})}return f((async()=>{await z(),t()})),j(O),x({type:1,page:1}).then((t=>{0==t.data.list.data.length&&(w=1)})),z(),(t,p)=>{const f=g(s("uni-nav-bar"),c),j=g(s("up-tabs"),_),b=g(s("fc-star-investor-list"),y),x=g(s("up-skeleton"),k),C=g(s("up-empty"),v),S=g(s("up-loadmore"),h),z=m;return a(),o(u,null,[r(f,{title:"明星投资者","status-bar":"","background-color":"#EB5528",color:"#fff","right-text":"优投记录",fixed:"",border:!1,"left-icon":"left",onClickLeft:i(d),onClickRight:B},null,8,["onClickLeft"]),0==i(w)?(a(),e(j,{key:0,list:i(A),scrollable:!1,current:i(D),onChange:p[0]||(p[0]=t=>D.value=t.index)},null,8,["list","current"])):n("",!0),r(z,{class:"body p-30"},{default:l((()=>[r(x,{loading:i(N),rows:"5",title:!1,"rows-height":140,isArticle:!1},{default:l((()=>[r(b,{list:i(L)},null,8,["list"])])),_:1},8,["loading"]),i(q)?(a(),e(C,{key:0,mode:"data",icon:"/static/empty/empty-data.png"})):n("",!0),i(q)||i(N)?n("",!0):(a(),e(S,{key:1,status:i(Z),onClick:i(O)},null,8,["status","onClick"]))])),_:1})],64)}}},[["__scopeId","data-v-fd53f479"]]);export{w as default};
