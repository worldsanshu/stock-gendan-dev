import{R as t,aj as e,x as a,y as s,D as o,H as l,A as i,B as c,Y as n,z as r,G as d,F as u,C as m,I as f,J as p,E as _,ah as h,aI as g,q as k}from"./index-DxUBkcQO.js";import{_ as y}from"./u-skeleton.CGJIjGk_.js";import{c as x,d as j,r as w}from"./uni-app.es.WyEFFhdr.js";import{_ as b}from"./u-empty.B-uqFSHu.js";import{_ as R}from"./u-loadmore.BbrrZ_01.js";import{o as C,r as $}from"./stock.Dg2EoKRb.js";import{u as v}from"./use-data-loader.47SkjMx6.js";import{_ as D}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./u-icon.BCEdui8G.js";import"./u-line.BXTok7nE.js";import"./u-loading-icon.DIzsuW0j.js";const I=D({__name:"user-center-stock-list",setup(D){const{t:I}=t(),{list:L,refresh:A,loadMore:B,loadStatus:E,loading:N,noData:q}=v(C);return A(),x((async()=>{await A(),e()})),j(B),(t,e)=>{const x=m,j=w(a("up-skeleton"),y),C=w(a("up-empty"),b),v=w(a("up-loadmore"),R);return s(),o(u,null,[l(x,null,{default:i((()=>[l(x,{class:"header border-bottom"},{default:i((()=>[l(x,{class:"header-item font-14-bold"},{default:i((()=>[f(p(t.$t("watchlist.text.stockName")),1)])),_:1}),l(x,{class:"header-item font-14-bold"},{default:i((()=>[f(p(t.$t("watchlist.text.latestPrice")),1)])),_:1}),l(x,{class:"header-item font-14-bold"},{default:i((()=>[f(p(t.$t("watchlist.text.increase")),1)])),_:1}),l(x,{class:"header-item font-14-bold"},{default:i((()=>[f(p(t.$t("watchlist.text.decrease")),1)])),_:1})])),_:1})])),_:1}),l(j,{class:c(["list plr-30",{"pt-30":n(N)}]),rows:"10","rows-height":50,title:!1,isArticle:!1,loading:n(N)},{default:i((()=>[(s(!0),o(u,null,_(n(L),((t,e)=>(s(),r(x,{class:"list-item border-bottom",onClick:e=>n(h)({name:t.name,code:t.code}),onLongpress:a=>function(t,e){g({itemList:[I("watchlist.text.tip1")],success:async a=>{if(0===a.tapIndex){const{error:a,message:s}=await $({code:t.code});await k({title:s,icon:a?"none":"success"}),a||L.value.splice(e,1)}}})}(t,e),key:t.id},{default:i((()=>[l(x,{class:"list-item-col"},{default:i((()=>[l(x,{class:"font-14"},{default:i((()=>[f(p(t.name),1)])),_:2},1024),l(x,{class:"font-12-grey"},{default:i((()=>[f(p(t.code),1)])),_:2},1024)])),_:2},1024),t.changeRatio>0?(s(),r(x,{key:0,class:"list-item-col font-14-red"},{default:i((()=>[f(p(t.current_price),1)])),_:2},1024)):(s(),r(x,{key:1,class:"list-item-col font-14-green"},{default:i((()=>[f(p(t.current_price),1)])),_:2},1024)),t.changeRatio>0?(s(),r(x,{key:2,class:"list-item-col font-14-red"},{default:i((()=>[f(p(t.changeRatio)+"%",1)])),_:2},1024)):(s(),r(x,{key:3,class:"list-item-col font-14-green"},{default:i((()=>[f(p(t.changeRatio)+"%",1)])),_:2},1024)),t.changeRatio>0?(s(),r(x,{key:4,class:"list-item-col font-14-red"},{default:i((()=>[f(p(t.change),1)])),_:2},1024)):(s(),r(x,{key:5,class:"list-item-col font-14-green"},{default:i((()=>[f(p(t.change),1)])),_:2},1024))])),_:2},1032,["onClick","onLongpress"])))),128))])),_:1},8,["class","loading"]),n(q)?(s(),r(C,{key:0,mode:"data",icon:"/static/empty/empty-data.png",text:t.$t("common.text.noData")},null,8,["text"])):d("",!0),n(q)||n(N)?d("",!0):(s(),r(v,{key:1,status:n(E),onClick:n(B)},null,8,["status","onClick"]))],64)}}},[["__scopeId","data-v-d886f230"]]);export{I as default};
