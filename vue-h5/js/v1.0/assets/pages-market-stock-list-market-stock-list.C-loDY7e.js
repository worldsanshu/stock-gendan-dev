import{ah as t,x as e,y as a,D as o,H as s,Y as l,A as r,F as i,C as n,Z as c,I as d,J as m,B as u,E as f,z as p,G as _,af as k}from"./index-BpNRNyt1.js";import{_ as x}from"./uni-nav-bar.DzQ3GIPc.js";import{o as g,a as y,r as b}from"./uni-app.es.DOVXrixg.js";import{_ as j}from"./u-skeleton.C1ZdLD0X.js";import{_ as $}from"./u-empty.DrFzq4Ux.js";import{_ as h}from"./u-loadmore.Cg4vISnZ.js";import{m as C}from"./stock.BWxM4DIu.js";import{u as v}from"./use-data-loader.CI72CnAM.js";import{_ as L}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.BdLyHM4-.js";import"./uni-status-bar.BKUrsIDI.js";import"./u-icon.Bew-d3Qt.js";import"./u-line.DJkOHe4f.js";import"./u-loading-icon.ByX6rnJ-.js";const F=L({__name:"market-stock-list",props:{code:String,naviBarTitle:String},setup(L){const F=L,{list:w,refresh:B,loadMore:D,loadStatus:M,loading:S,noData:T}=v(C,{industry_code:F.code});return B(),g((async()=>{await B(),t()})),y(D),(t,g)=>{const y=b(e("uni-nav-bar"),x),C=n,v=b(e("up-skeleton"),j),F=b(e("up-empty"),$),B=b(e("up-loadmore"),h);return a(),o(i,null,[s(y,{title:L.naviBarTitle,"status-bar":"","left-icon":"left","background-color":"#F8F8F8",fixed:"",border:!1,onClickLeft:l(c)},null,8,["title","onClickLeft"]),s(C,{class:"root"},{default:r((()=>[s(C,{class:"header border-bottom"},{default:r((()=>[s(C,{class:"header-item font-14-bold"},{default:r((()=>[d(m(t.$t("stockList.text.name")),1)])),_:1}),s(C,{class:"header-item font-14-bold"},{default:r((()=>[d(m(t.$t("stockList.text.latestPrice")),1)])),_:1}),s(C,{class:"header-item font-14-bold"},{default:r((()=>[d(m(t.$t("stockList.text.amplitude")),1)])),_:1})])),_:1}),s(v,{class:u(["list plr-30",{"pt-30":l(S)}]),rows:"10","rows-height":50,title:!1,isArticle:!1,loading:l(S)},{default:r((()=>[(a(!0),o(i,null,f(l(w),(t=>(a(),p(C,{class:"list-item border-bottom",key:t.id,onClick:e=>l(k)({name:t.name,code:t.code})},{default:r((()=>[s(C,{class:"list-item-col"},{default:r((()=>[s(C,{class:"font-14"},{default:r((()=>[d(m(t.name),1)])),_:2},1024),s(C,{class:"font-12-grey"},{default:r((()=>[d(m(t.code),1)])),_:2},1024)])),_:2},1024),t.current_rate>0?(a(),p(C,{key:0,class:"list-item-col font-14-red"},{default:r((()=>[d(m(t.current_price),1)])),_:2},1024)):(a(),p(C,{key:1,class:"list-item-col font-14-green"},{default:r((()=>[d(m(t.current_price),1)])),_:2},1024)),t.current_rate>0?(a(),p(C,{key:2,class:"list-item-col font-14-red"},{default:r((()=>[d(m(`${t.current_rate}%`),1)])),_:2},1024)):(a(),p(C,{key:3,class:"list-item-col font-14-green"},{default:r((()=>[d(m(`${t.current_rate}%`),1)])),_:2},1024))])),_:2},1032,["onClick"])))),128))])),_:1},8,["class","loading"]),l(T)?(a(),p(F,{key:0,mode:"data",icon:"/static/empty/empty-data.png",text:t.$t("common.text.noData")},null,8,["text"])):_("",!0),l(T)||l(S)?_("",!0):(a(),p(B,{key:1,status:l(M),onClick:l(D),"loading-text":t.$t("common.text.loading"),"loadmore-text":t.$t("common.text.loadMore"),"nomore-text":t.$t("common.text.noMore")},null,8,["status","onClick","loading-text","loadmore-text","nomore-text"]))])),_:1})],64)}}},[["__scopeId","data-v-20f5d94e"]]);export{F as default};
