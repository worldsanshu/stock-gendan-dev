import{ai as t,x as s,y as a,D as o,H as r,Y as i,z as e,G as n,A as l,F as u,aq as p,C as m,Z as d}from"./index-BpfZrajd.js";import{_ as c}from"./uni-nav-bar.BZQCfLjz.js";import{o as f,a as j,r as g}from"./uni-app.es.C3RDKkKF.js";import{_}from"./u-tabs.CxSgs4yZ.js";import{u as y,_ as b}from"./use-star-investor.BM48RvQY.js";import{_ as k}from"./u-skeleton.B5Rn11cu.js";import{_ as v}from"./u-empty.BAQ49pnu.js";import{_ as x}from"./u-loadmore.Bz-sBCou.js";import{O as h}from"./index.B50efdrW.js";import{_ as C}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.CkblPvCD.js";import"./uni-status-bar.CQB75Tz1.js";import"./u-avatar.DjXPBxle.js";import"./u-icon.B3VNGynq.js";import"./u-text.D6qWDwyX.js";import"./u-link.BjavWkwF.js";import"./u-tag.COU-YiZl.js";import"./u-transition.CT0rDcrB.js";import"./use-data-loader.CT7HjI1q.js";import"./u-line.CQDya69Z.js";import"./u-loading-icon.DWuwejaM.js";const w=C({__name:"star-investor-v2-copy",props:{type:String},setup(C){let w=0;const A=C,{tabs:D,tabCurrent:L,list:O,loading:R,loadMore:S,loadStatus:q,noData:z,refresh:B}=y(Number(A.type));function E(){p("/pages/record/record",{id:20})}return f((async()=>{await B(),t()})),j(S),h({type:1,page:1}).then((t=>{0==t.data.list.data.length&&(w=1)})),B(),(t,p)=>{const f=g(s("uni-nav-bar"),c),j=g(s("up-tabs"),_),y=g(s("fc-star-investor-list"),b),h=g(s("up-skeleton"),k),C=g(s("up-empty"),v),A=g(s("up-loadmore"),x),B=m;return a(),o(u,null,[r(f,{title:"明星投资者","status-bar":"","background-color":"#EB5528",color:"#fff","right-text":"优投记录",fixed:"",border:!1,"left-icon":"left",onClickLeft:i(d),onClickRight:E},null,8,["onClickLeft"]),0==i(w)?(a(),e(j,{key:0,list:i(D),scrollable:!1,current:i(L),onChange:p[0]||(p[0]=t=>L.value=t.index)},null,8,["list","current"])):n("",!0),r(B,{class:"body p-30"},{default:l((()=>[r(h,{loading:i(R),rows:"5",title:!1,"rows-height":140,isArticle:!1},{default:l((()=>[r(y,{list:i(O)},null,8,["list"])])),_:1},8,["loading"]),i(z)?(a(),e(C,{key:0,mode:"data",icon:"/static/empty/empty-data.png"})):n("",!0),i(z)||i(R)?n("",!0):(a(),e(A,{key:1,status:i(q),onClick:i(S)},null,8,["status","onClick"]))])),_:1})],64)}}},[["__scopeId","data-v-fd53f479"]]);export{w as default};