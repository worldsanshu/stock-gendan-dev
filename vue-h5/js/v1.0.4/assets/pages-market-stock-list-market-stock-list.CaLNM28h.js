import{aj as t,x as e,y as a,D as o,H as s,Y as l,A as r,F as i,C as n,Z as c,I as d,J as m,B as u,E as f,z as p,G as _,ah as k}from"./index-DXZh4TrB.js";import{_ as x}from"./uni-nav-bar.HncgR8y-.js";import{c as g,d as j,r as y}from"./uni-app.es.BpJJwZA9.js";import{_ as b}from"./u-skeleton.BoxUIRYJ.js";import{_ as h}from"./u-empty.CV-Dq1M_.js";import{_ as $}from"./u-loadmore.C3IN5QC8.js";import{m as v}from"./stock.CP0grtws.js";import{u as C}from"./use-data-loader.DU5ym27M.js";import{_ as L}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.CnM6HZsg.js";import"./uni-status-bar.BUxDt1qs.js";import"./u-icon.CtR5oXjZ.js";import"./u-line.DIn8oqBr.js";import"./u-loading-icon.D8mSLZ2D.js";const w=L({__name:"market-stock-list",props:{code:String,naviBarTitle:String},setup(L){const w=L,{list:B,refresh:D,loadMore:F,loadStatus:I,loading:M,noData:S}=C(v,{industry_code:w.code});return D(),g((async()=>{await D(),t()})),j(F),(t,g)=>{const j=y(e("uni-nav-bar"),x),v=n,C=y(e("up-skeleton"),b),w=y(e("up-empty"),h),D=y(e("up-loadmore"),$);return a(),o(i,null,[s(j,{title:L.naviBarTitle,"status-bar":"","left-icon":"left","background-color":"#F8F8F8",fixed:"",border:!1,onClickLeft:l(c)},null,8,["title","onClickLeft"]),s(v,{class:"root"},{default:r((()=>[s(v,{class:"header border-bottom"},{default:r((()=>[s(v,{class:"header-item font-14-bold"},{default:r((()=>[d(m(t.$t("stockList.text.name")),1)])),_:1}),s(v,{class:"header-item font-14-bold"},{default:r((()=>[d(m(t.$t("stockList.text.latestPrice")),1)])),_:1}),s(v,{class:"header-item font-14-bold"},{default:r((()=>[d(m(t.$t("stockList.text.amplitude")),1)])),_:1})])),_:1}),s(C,{class:u(["list plr-30",{"pt-30":l(M)}]),rows:"10","rows-height":50,title:!1,isArticle:!1,loading:l(M)},{default:r((()=>[(a(!0),o(i,null,f(l(B),(t=>(a(),p(v,{class:"list-item border-bottom",key:t.id,onClick:e=>l(k)({name:t.name,code:t.code})},{default:r((()=>[s(v,{class:"list-item-col"},{default:r((()=>[s(v,{class:"font-14"},{default:r((()=>[d(m(t.name),1)])),_:2},1024),s(v,{class:"font-12-grey"},{default:r((()=>[d(m(t.code),1)])),_:2},1024)])),_:2},1024),t.current_rate>0?(a(),p(v,{key:0,class:"list-item-col font-14-red"},{default:r((()=>[d(m(t.current_price),1)])),_:2},1024)):(a(),p(v,{key:1,class:"list-item-col font-14-green"},{default:r((()=>[d(m(t.current_price),1)])),_:2},1024)),t.current_rate>0?(a(),p(v,{key:2,class:"list-item-col font-14-red"},{default:r((()=>[d(m(`${t.current_rate}%`),1)])),_:2},1024)):(a(),p(v,{key:3,class:"list-item-col font-14-green"},{default:r((()=>[d(m(`${t.current_rate}%`),1)])),_:2},1024))])),_:2},1032,["onClick"])))),128))])),_:1},8,["class","loading"]),l(S)?(a(),p(w,{key:0,mode:"data",icon:"/static/empty/empty-data.png",text:t.$t("common.text.noData")},null,8,["text"])):_("",!0),l(S)||l(M)?_("",!0):(a(),p(D,{key:1,status:l(I),onClick:l(F),"loading-text":t.$t("common.text.loading"),"loadmore-text":t.$t("common.text.loadMore"),"nomore-text":t.$t("common.text.noMore")},null,8,["status","onClick","loading-text","loadmore-text","nomore-text"]))])),_:1})],64)}}},[["__scopeId","data-v-20f5d94e"]]);export{w as default};
