import{a1 as t,a2 as e,a3 as a,a4 as s,a5 as l,a6 as i,a7 as n,a8 as o,y as r,z as c,A as u,a9 as d,B as f,aa as m,G as p,C as h,R as _,u as g,ab as y,V as x,ac as k,ad as v,ae as b,x as w,D as C,H as q,F as $,q as j,Y as S,b as D,l as L,E as T,L as F,M as I,I as N,J as A,af as P,ag as M,ah as z}from"./index-BKBmZM5g.js";import{_ as H}from"./u-image.b9YyUWhy.js";import{r as G}from"./uni-app.es.CT9bmSuD.js";import{_ as E}from"./u-search.B-hmfomJ.js";import{_ as O}from"./u-icon.ChK8Jwfk.js";import{_ as B}from"./u-navbar.Dmcct0Y_.js";import{_ as R}from"./u-status-bar.B72p7bd1.js";import{_ as W,a as U}from"./u-row.Sf5tT6W6.js";import{_ as Y}from"./u-skeleton.CtQUZt4A.js";import{_ as K}from"./u-subsection.Cium7fgE.js";import{_ as J}from"./_plugin-vue_export-helper.BCo6x5W8.js";import{_ as V}from"./l-echart.XiFwINgY.js";import{_ as Q}from"./u-button.CPApju8R.js";import{_ as X}from"./u-sticky.CVATSJLr.js";import{_ as Z}from"./u-tag.CaftJmK3.js";import{g as tt,a as et,b as at,c as st,d as lt,e as it,f as nt,h as ot,i as rt,j as ct,k as ut,l as dt}from"./stock.BuiTL17Z.js";import{L as ft,e as mt}from"./index.2dlPunij.js";import"./u-transition.DI_txXdd.js";import"./u-loading-icon.BDLNhgIp.js";const pt=J({name:"u-grid-item",mixins:[e,a,{props:{name:{type:[String,Number,null],default:()=>t.gridItem.name},bgColor:{type:String,default:()=>t.gridItem.bgColor}}}],data:()=>({parentData:{col:0,border:!0},classes:[]}),mounted(){this.init()},emits:["click"],computed:{width(){return this.parentData.col>0?100/Number(this.parentData.col)+"%":0},itemStyle(){const t={background:this.bgColor,width:this.width};return s(t,l(this.customStyle))}},methods:{init(){i("$uGridItem",(()=>{this.gridItemClasses()})),this.updateParentData(),n("$uGridItem"),this.gridItemClasses()},updateParentData(){this.getParentData("u-grid")},clickHandler(){var t;let e=this.name;const a=null==(t=this.parent)?void 0:t.children;a&&null===this.name&&(e=a.findIndex((t=>t===this))),this.parent&&this.parent.childClick(e),this.$emit("click",e)},async getItemWidth(){let t=0;if(this.parent){t=await this.getParentWidth()/Number(this.parentData.col)+"px"}this.width=t},getParentWidth(){},gridItemClasses(){if(this.parentData.border){let t=[];this.parent.children.map(((e,a)=>{if(this===e){const e=this.parent.children.length;(a+1)%this.parentData.col!=0&&a+1!==e&&t.push("u-border-right");a<e-(e%this.parentData.col==0?this.parentData.col:e%this.parentData.col)&&t.push("u-border-bottom")}})),this.classes=t}}},beforeUnmount(){o("$uGridItem")}},[["render",function(t,e,a,s,l,i){const n=h;return l.parentData.col>0?(r(),c(n,{key:0,class:f(["u-grid-item",l.classes]),"hover-class":"u-grid-item--hover-class","hover-stay-time":200,onClick:i.clickHandler,style:m([i.itemStyle])},{default:u((()=>[d(t.$slots,"default",{},void 0,!0)])),_:3},8,["onClick","class","style"])):p("",!0)}],["__scopeId","data-v-7b09518e"]]);const ht=J({name:"u-grid",mixins:[e,a,{props:{col:{type:[String,Number],default:()=>t.grid.col},border:{type:Boolean,default:()=>t.grid.border},align:{type:String,default:()=>t.grid.align}}}],data:()=>({index:0,width:0}),watch:{parentData(){this.children.length&&this.children.map((t=>{"function"==typeof t.updateParentData&&t.updateParentData()}))}},created(){this.children=[]},computed:{parentData(){return[this.hoverClass,this.col,this.size,this.border]},gridStyle(){let t={};switch(this.align){case"left":default:t.justifyContent="flex-start";break;case"center":t.justifyContent="center";break;case"right":t.justifyContent="flex-end"}return s(t,l(this.customStyle))}},emits:["click"],methods:{childClick(t){this.$emit("click",t)}}},[["render",function(t,e,a,s,l,i){const n=h;return r(),c(n,{class:"u-grid",ref:"u-grid",style:m([i.gridStyle])},{default:u((()=>[d(t.$slots,"default",{},void 0,!0)])),_:3},8,["style"])}],["__scopeId","data-v-046894f6"]]),_t=J({__name:"market-v2",setup(t){const{t:e}=_(),a=g(),s=y([]),l=y([]),i=y([]),n=y([]),o=y([]),d=y([]),m=y([]),p=y([]),J=x([]),_t=x([]),gt=[e("quotes.text.stockListTitle1"),e("quotes.text.stockListTitle2"),e("quotes.text.stockListTitle3")],yt=[e("quotes.text.risingStars"),e("quotes.text.fallingList")],xt=[{name:e("quotes.text.stockTag1"),apiFunction:tt},{name:e("quotes.text.stockTag2"),apiFunction:et},{name:e("quotes.text.stockTag3"),apiFunction:at},{name:e("quotes.text.stockTag4"),apiFunction:st}],kt=[{name:e("quotes.text.stockTag5"),cat:"FIN"},{name:e("quotes.text.stockTag6"),cat:"TEC"},{name:e("quotes.text.stockTag7"),cat:"MAR"},{name:e("quotes.text.stockTag8"),cat:"AAE"}],vt=x(0),bt=x(0),wt=x(0),Ct=x(0),qt=x(!0),$t=x(!0),jt=x(!0),St=x(!0),Dt=x(!0),Lt=x(!0),Tt=k.statusBarHeight+44;async function Ft(){const{error:t,message:e,data:a}=await xt[wt.value].apiFunction();t?await j({title:e,icon:"none"}):(m.value=a,Dt.value=!1)}async function It(){const{cat:t}=kt[Ct.value],{error:e,message:a,data:s}=await dt({cat:t});e?await j({title:a,icon:"none"}):(p.value=s,Lt.value=!1)}!async function(){const{error:t,message:e,data:a}=await lt({code:"sh000001,399001,399006"});t?await j({title:e,icon:"none"}):(s.value=a,qt.value=!1)}(),async function(){const{error:t,message:e,data:a}=await it();t?await j({title:e,icon:"none"}):(n.value=a.sort(((t,e)=>e[5]-t[5])).slice(0,6),$t.value=!1)}(),v((()=>{0===bt.value?async function(){const{error:t,message:e,data:a}=await ct();t?await j({title:e,icon:"none"}):(o.value=a,jt.value=!1)}():async function(){const{error:t,message:e,data:a}=await ut();t?await j({title:e,icon:"none"}):(d.value=a,St.value=!1)}()})),b(vt,(t=>{1===t?(!async function(){const{error:t,message:e,data:a}=await nt({from:"hkIndex"});if(t)return void(await j({title:e,icon:"none"}));l.value=a,await z();const s=await ot();s.error?await j({title:s.message,icon:"none"}):1===vt.value&&l.value.forEach(((t,e)=>{if(1!==vt.value)return;const a=s.data[t.code.replace("r_hk","")][0],l=a.map((t=>({name:a[0].date+" "+t.m,value:[a[0].date+" "+t.m,Number(t.price)]}))),i=J.value[e];Number(t.diff)>0?Pt(i,l):Mt(i,l)}))}(),Ft()):2===t&&(!async function(){const{error:t,message:e,data:a}=await nt({from:"gbIndex"});if(t)return void(await j({title:e,icon:"none"}));i.value=a,await z();const s=await rt();s.error?await j({title:s.message,icon:"none"}):2===vt.value&&i.value.forEach(((t,e)=>{if(2!==vt.value)return;const a=s.data.findIndex((t=>t.name==t.name)),l=s.data[a].list.map((t=>({name:Nt.toString(),value:[At+" "+t.time,Number(t.price)]}))),i=_t.value[e];Number(t.diff)>0?Pt(i,l):Mt(i,l)}))}(),It())})),b(wt,Ft),b(Ct,It);const Nt=new Date,At=Nt.getFullYear()+"-"+(Nt.getMonth()+1)+"-"+Nt.getDate();async function Pt(t,e){const a={tooltip:{show:!1},xAxis:{type:"time",splitLine:{show:!1},axisLine:{show:!1},axisTick:{show:!1},axisLabel:{show:!1}},yAxis:{type:"value",boundaryGap:[0,"100%"],splitLine:{show:!1},axisLabel:{show:!1},axisLine:{show:!1},axisTick:{show:!1},min:t=>t.min,max:t=>t.max},grid:{top:10,bottom:"20px"},series:[{type:"line",showSymbol:!1,data:e,itemStyle:{normal:{color:"#e2170c"}},areaStyle:{normal:{color:new ft(0,0,0,1,[{offset:0,color:"#fcf3f5"},{offset:1,color:"#FFFFFF"}])}}}]};if(t.chart)return void t.chart.setOption(a);(await t.init(mt)).setOption(a)}async function Mt(t,e){const a={tooltip:{show:!1},xAxis:{type:"time",splitLine:{show:!1},axisLine:{show:!1},axisTick:{show:!1},axisLabel:{show:!1}},yAxis:{type:"value",boundaryGap:[0,"100%"],splitLine:{show:!1},axisLabel:{show:!1},axisLine:{show:!1},axisTick:{show:!1},min:t=>t.min,max:t=>t.max},grid:{top:10,bottom:"1.25rem"},series:[{type:"line",showSymbol:!1,data:e,itemStyle:{normal:{color:"#4e8761"}}}]};if(t.chart)return void t.chart.setOption(a);(await t.init(mt)).setOption(a)}function zt(){j({title:e("quotes.text.contactCustomerService"),icon:"none"})}function Ht(){j({title:e("quotes.text.contactCustomerService"),icon:"none"})}return(t,e)=>{const _=G(w("up-image"),H),g=G(w("up-search"),E),y=h,x=G(w("up-icon"),O),k=G(w("up-navbar"),B),v=G(w("u-status-bar"),R),b=G(w("up-col"),W),j=G(w("up-row"),U),z=G(w("up-skeleton"),Y),tt=G(w("up-subsection"),K),et=G(w("up-grid-item"),pt),at=G(w("up-grid"),ht),st=G(w("l-echart"),V),lt=G(w("up-button"),Q),it=G(w("up-sticky"),X),nt=G(w("up-tag"),Z);return r(),C($,null,[q(k,{style:{"margin-bottom":"44px"},"left-icon":""},{center:u((()=>[q(y,{class:"navbar-content"},{default:u((()=>[q(_,{class:"plr-20",width:"54rpx",height:"54rpx",src:S(a).appConfig.logo},null,8,["src"]),q(y,{style:{flex:"1"},onClick:S(D)},{default:u((()=>[q(g,{placeholder:t.$t("quotes.text.search"),"show-action":!1},null,8,["placeholder"])])),_:1},8,["onClick"]),q(x,{class:"plr-20",name:"kefu-ermai",onClick:S(L),color:"#2979ff",size:"20"},null,8,["onClick"])])),_:1})])),_:1}),q(v),q(y,{class:"page-content"},{default:u((()=>[q(z,{rows:"1","rows-height":"180rpx",title:!1,isArticle:!1,loading:qt.value},{default:u((()=>[q(j,{justify:"space-between",gutter:"10"},{default:u((()=>[(r(!0),C($,null,T(s.value,(t=>(r(),c(b,{span:"4",key:t.id},{default:u((()=>[q(y,{class:"market-index-item"},{default:u((()=>[q(y,{class:"font-16"},{default:u((()=>[N(A(t.name),1)])),_:2},1024),t.price_rate>0?(r(),c(y,{key:0,class:"font-16-red mt-10"},{default:u((()=>[N(A(t.current_price),1)])),_:2},1024)):(r(),c(y,{key:1,class:"font-16-green mt-10"},{default:u((()=>[N(A(t.current_price),1)])),_:2},1024)),q(y,{class:"between-center mt-10"},{default:u((()=>[t.price_range>0?(r(),c(y,{key:0,class:"font-12-red"},{default:u((()=>[N("+"+A(t.price_range),1)])),_:2},1024)):(r(),c(y,{key:1,class:"font-12-green"},{default:u((()=>[N(A(t.price_range),1)])),_:2},1024)),t.price_rate>0?(r(),c(y,{key:2,class:"font-12-red"},{default:u((()=>[N(A(t.price_rate)+"%",1)])),_:2},1024)):(r(),c(y,{key:3,class:"font-12-green"},{default:u((()=>[N(A(t.price_rate)+"%",1)])),_:2},1024))])),_:2},1024)])),_:2},1024)])),_:2},1024)))),128))])),_:1})])),_:1},8,["loading"]),q(tt,{class:"mt-30",mode:"subsection",list:gt,current:vt.value,"active-color":"#eb5528",onChange:e[0]||(e[0]=t=>vt.value=t)},null,8,["current"]),F(q(y,null,{default:u((()=>[q(z,{class:"mt-30",rows:"2","rows-height":"200rpx",title:!1,isArticle:!1,loading:$t.value},{default:u((()=>[q(at,{border:!0},{default:u((()=>[(r(!0),C($,null,T(n.value,(t=>(r(),c(et,{class:"p-20",key:t.id,onClick:e=>S(P)({code:t[0],naviBarTitle:t[1]})},{default:u((()=>[q(y,{class:"font-16"},{default:u((()=>[N(A(t[1]),1)])),_:2},1024),t[5]>0?(r(),c(y,{key:0,class:"mt-5 font-16-red"},{default:u((()=>[N(A((Math.round(100*t[5])/100).toFixed(2)+"%"),1)])),_:2},1024)):(r(),c(y,{key:1,class:"mt-5 font-16-green"},{default:u((()=>[N(A((Math.round(100*t[5])/100).toFixed(2)+"%"),1)])),_:2},1024)),q(y,{class:"mt-5 font-12-red"},{default:u((()=>[N(A(t[12]),1)])),_:2},1024),q(y,{class:"mt-5 between-center",style:{"align-self":"stretch"}},{default:u((()=>[q(y,{class:"font-12"},{default:u((()=>[N(A(t[10]),1)])),_:2},1024),q(y,{class:"font-12"},{default:u((()=>[N(A(t[9]),1)])),_:2},1024)])),_:2},1024)])),_:2},1032,["onClick"])))),128))])),_:1})])),_:1},8,["loading"]),q(y,{class:"center-center mt-30"},{default:u((()=>[q(tt,{class:"mt-30",style:{width:"50%"},mode:"subsection",list:yt,current:bt.value,"active-color":"#eb5528",onChange:e[1]||(e[1]=t=>bt.value=t)},null,8,["current"])])),_:1}),q(z,{class:f({"pt-20":0===bt.value&&jt.value||1===bt.value&&St.value}),rows:"6","rows-height":"110rpx",title:!1,isArticle:!1,loading:0===bt.value&&jt.value||1===bt.value&&St.value},{default:u((()=>[F(q(y,{class:"list"},{default:u((()=>[(r(!0),C($,null,T(o.value,(t=>(r(),c(y,{class:"list-item border-bottom",key:t.id,onClick:e=>S(M)({name:t.name,code:t.code})},{default:u((()=>[q(y,{class:"list-item-col"},{default:u((()=>[q(y,{class:"font-14"},{default:u((()=>[N(A(t.name),1)])),_:2},1024),q(y,{class:"font-12-grey"},{default:u((()=>[N(A(t.code),1)])),_:2},1024)])),_:2},1024),q(y,{class:"list-item-col font-14 text-align-center"},{default:u((()=>[N(A(t.trade),1)])),_:2},1024),q(y,{class:"list-item-col font-14-red text-align-right"},{default:u((()=>[N(A(t.changepercent)+"%",1)])),_:2},1024)])),_:2},1032,["onClick"])))),128))])),_:1},512),[[I,0===bt.value]]),F(q(y,{class:"list"},{default:u((()=>[(r(!0),C($,null,T(d.value,(t=>(r(),c(y,{class:"list-item border-bottom",key:t.id,onClick:e=>S(M)({name:t.name,code:t.code})},{default:u((()=>[q(y,{class:"list-item-col"},{default:u((()=>[q(y,{class:"font-14"},{default:u((()=>[N(A(t.name),1)])),_:2},1024),q(y,{class:"font-12-grey"},{default:u((()=>[N(A(t.code),1)])),_:2},1024)])),_:2},1024),q(y,{class:"list-item-col font-14 text-align-center"},{default:u((()=>[N(A(t.trade),1)])),_:2},1024),q(y,{class:"list-item-col font-14-green text-align-right"},{default:u((()=>[N(A(t.changepercent)+"%",1)])),_:2},1024)])),_:2},1032,["onClick"])))),128))])),_:1},512),[[I,1===bt.value]])])),_:1},8,["class","loading"])])),_:1},512),[[I,0===vt.value]]),F(q(y,null,{default:u((()=>[q(j,{class:"mt-30",justify:"space-between",gutter:"10"},{default:u((()=>[(r(!0),C($,null,T(l.value,(t=>(r(),c(b,{span:"4",key:t.id},{default:u((()=>[q(y,{class:"index-item"},{default:u((()=>[q(y,{class:"font-16 mb-20"},{default:u((()=>[N(A(t.name),1)])),_:2},1024),q(st,{ref_for:!0,ref_key:"hkChartRefs",ref:J,style:{height:"100px"}},null,512),t.rate>0?(r(),c(y,{key:0,class:"mt-20 font-14-red"},{default:u((()=>[N(A(t.current_price),1)])),_:2},1024)):(r(),c(y,{key:1,class:"mt-20 font-14-green"},{default:u((()=>[N(A(t.current_price),1)])),_:2},1024)),q(y,{class:"mt-10 between-center"},{default:u((()=>[Number(t.diff)>0?(r(),c(y,{key:0,class:"font-12-red"},{default:u((()=>[N(A(t.diff),1)])),_:2},1024)):(r(),c(y,{key:1,class:"font-12-green"},{default:u((()=>[N(A(t.diff),1)])),_:2},1024)),Number(t.rate)>0?(r(),c(y,{key:2,class:"font-12-red"},{default:u((()=>[N(A(t.rate),1)])),_:2},1024)):(r(),c(y,{key:3,class:"font-12-green"},{default:u((()=>[N(A(t.rate),1)])),_:2},1024))])),_:2},1024)])),_:2},1024)])),_:2},1024)))),128))])),_:1}),q(y,{class:"stock-info mt-30"},{default:u((()=>[q(y,{class:"stock-info-header"},{default:u((()=>[q(y,{class:"font-12-green"},{default:u((()=>[N(A(t.$t("quotes.text.fall"))+" 867",1)])),_:1}),q(y,{class:"font-12-red"},{default:u((()=>[N(A(t.$t("quotes.text.rise"))+" 506",1)])),_:1})])),_:1}),q(y,{class:"progress mt-20"},{default:u((()=>[q(y,{class:"progress-item"}),q(y,{class:"progress-item"}),q(y,{class:"progress-item"})])),_:1}),q(y,{class:"stock-info-content mt-20"},{default:u((()=>[q(y,{class:"stock-info-content-item"},{default:u((()=>[q(y,{class:"font-12"},{default:u((()=>[N(A(t.$t("quotes.text.tip1")),1)])),_:1}),q(y,{class:"font-14-red mt-10"},{default:u((()=>[N(A(t.$t("quotes.text.tip3")),1)])),_:1})])),_:1}),q(y,{class:"stock-info-content-item"},{default:u((()=>[q(y,{class:"font-12"},{default:u((()=>[N(A(t.$t("quotes.text.tip2")),1)])),_:1}),q(y,{class:"font-14-green mt-10"},{default:u((()=>[N(A(t.$t("quotes.text.tip4")),1)])),_:1})])),_:1})])),_:1})])),_:1}),q(y,{class:"mt-30 font-20"},{default:u((()=>[N(A(t.$t("quotes.text.hongKongStockList")),1)])),_:1}),q(it,{"custom-nav-height":Tt},{default:u((()=>[q(y,{class:"bg-white"},{default:u((()=>[q(j,{class:"pt-20",gutter:"10"},{default:u((()=>[(r(),C($,null,T(xt,((t,e)=>q(b,{span:12/xt.length,key:t.name,onClick:t=>wt.value=e},{default:u((()=>[q(lt,{type:"error",color:"#eb5528",plain:wt.value!==e,size:"mini",shape:"circle",text:t.name},null,8,["plain","text"])])),_:2},1032,["span","onClick"]))),64))])),_:1}),q(y,{class:"header border-bottom mt-10"},{default:u((()=>[q(y,{class:"header-item font-14"},{default:u((()=>[N(A(t.$t("quotes.text.name"))+" | "+A(t.$t("quotes.text.code")),1)])),_:1}),q(y,{class:"header-item font-14"},{default:u((()=>[N(A(t.$t("quotes.text.latestPrice"))+" | "+A(t.$t("quotes.text.quoteChange")),1)])),_:1}),q(y,{class:"header-item font-14"},{default:u((()=>[N(A(t.$t("quotes.text.yesterdayHarvest"))+" | "+A(t.$t("quotes.text.openToday")),1)])),_:1})])),_:1})])),_:1})])),_:1}),q(z,{class:f({"pt-20":Dt.value}),rows:"6","rows-height":"110rpx","rows-width":"690rpx",title:!1,loading:Dt.value},{default:u((()=>[q(y,{class:"list"},{default:u((()=>[(r(!0),C($,null,T(m.value,(t=>(r(),c(y,{class:"list-item border-bottom",key:t.id,onClick:zt},{default:u((()=>[q(y,{class:"list-item-col text-align-left"},{default:u((()=>[q(y,{class:"font-14"},{default:u((()=>[N(A(t.t1),1)])),_:2},1024),q(y,{class:"mt-10 align-center"},{default:u((()=>[q(nt,{text:"HK",plain:"",size:"mini",type:"primary"}),q(y,{class:"ml-5 font-12-grey"},{default:u((()=>[N(A(t.t2),1)])),_:2},1024)])),_:2},1024)])),_:2},1024),q(y,{class:"list-item-col text-align-center"},{default:u((()=>[q(y,{class:"font-14"},{default:u((()=>[N(A(t.t3),1)])),_:2},1024),q(y,{class:"font-14"},{default:u((()=>[N(A((Math.round(100*t.t4)/100).toFixed(2)+"%"),1)])),_:2},1024)])),_:2},1024),q(y,{class:"list-item-col text-align-right"},{default:u((()=>[q(y,{class:"font-14"},{default:u((()=>[N(A(t.t5),1)])),_:2},1024),t.t4>0?(r(),c(y,{key:0,class:"font-12-red"},{default:u((()=>[N(A(t.t6),1)])),_:2},1024)):(r(),c(y,{key:1,class:"font-12-green"},{default:u((()=>[N(A(t.t6),1)])),_:2},1024))])),_:2},1024)])),_:2},1024)))),128))])),_:1})])),_:1},8,["class","loading"])])),_:1},512),[[I,1===vt.value]]),F(q(y,null,{default:u((()=>[q(j,{class:"mt-30",justify:"space-between",gutter:"10"},{default:u((()=>[(r(!0),C($,null,T(i.value,(t=>(r(),c(b,{span:"4",key:t.code},{default:u((()=>[q(y,{class:"index-item"},{default:u((()=>[q(y,{class:"font-16 mb-20"},{default:u((()=>[N(A(t.name),1)])),_:2},1024),q(st,{ref_for:!0,ref_key:"usChartRefs",ref:_t,style:{height:"100px"}},null,512),t.rate>0?(r(),c(y,{key:0,class:"mt-20 font-14-red"},{default:u((()=>[N(A(t.current_price),1)])),_:2},1024)):(r(),c(y,{key:1,class:"mt-20 font-14-green"},{default:u((()=>[N(A(t.current_price),1)])),_:2},1024)),q(y,{class:"mt-10 between-center"},{default:u((()=>[Number(t.diff)>0?(r(),c(y,{key:0,class:"font-12-red"},{default:u((()=>[N(A(t.diff),1)])),_:2},1024)):(r(),c(y,{key:1,class:"font-12-green"},{default:u((()=>[N(A(t.diff),1)])),_:2},1024)),Number(t.rate)>0?(r(),c(y,{key:2,class:"font-12-red"},{default:u((()=>[N(A(t.rate),1)])),_:2},1024)):(r(),c(y,{key:3,class:"font-12-green"},{default:u((()=>[N(A(t.rate),1)])),_:2},1024))])),_:2},1024)])),_:2},1024)])),_:2},1024)))),128))])),_:1}),q(y,{class:"mt-30 font-20"},{default:u((()=>[N(A(t.$t("quotes.text.usStockList")),1)])),_:1}),q(it,{"custom-nav-height":Tt},{default:u((()=>[q(y,{class:"bg-white"},{default:u((()=>[q(j,{class:"pt-20",gutter:"10"},{default:u((()=>[(r(),C($,null,T(kt,((t,e)=>q(b,{span:12/kt.length,key:t.name,onClick:t=>Ct.value=e},{default:u((()=>[q(lt,{type:"error",color:"#eb5528",plain:Ct.value!==e,size:"mini",shape:"circle",text:t.name},null,8,["plain","text"])])),_:2},1032,["span","onClick"]))),64))])),_:1}),q(y,{class:"header border-bottom mt-10"},{default:u((()=>[q(y,{class:"header-item font-14"},{default:u((()=>[N(A(t.$t("quotes.text.name"))+" | "+A(t.$t("quotes.text.code")),1)])),_:1}),q(y,{class:"header-item font-14"},{default:u((()=>[N(A(t.$t("quotes.text.latestPrice"))+" | "+A(t.$t("quotes.text.quoteChange")),1)])),_:1}),q(y,{class:"header-item font-14"},{default:u((()=>[N(A(t.$t("quotes.text.yesterdayHarvest"))+" | "+A(t.$t("quotes.text.openToday")),1)])),_:1})])),_:1})])),_:1})])),_:1}),q(z,{class:f({"pt-20":Lt.value}),rows:"6","rows-height":"110rpx","rows-width":"690rpx",title:!1,loading:Lt.value},{default:u((()=>[q(y,{class:"list"},{default:u((()=>[(r(!0),C($,null,T(p.value,(t=>(r(),c(y,{class:"list-item border-bottom",key:t.id,onClick:Ht},{default:u((()=>[q(y,{class:"list-item-col text-align-left"},{default:u((()=>[q(y,{class:"font-14"},{default:u((()=>[N(A(t.t1),1)])),_:2},1024),q(y,{class:"mt-10 align-center"},{default:u((()=>[q(nt,{text:"US",plain:"",size:"mini",type:"primary"}),q(y,{class:"ml-5 font-12-grey"},{default:u((()=>[N(A(t.t2),1)])),_:2},1024)])),_:2},1024)])),_:2},1024),q(y,{class:"list-item-col text-align-center"},{default:u((()=>[q(y,{class:"font-14"},{default:u((()=>[N(A(t.t3),1)])),_:2},1024),t.t4>0?(r(),c(y,{key:0,class:"font-12-red"},{default:u((()=>[N(A((Math.round(100*t.t4)/100).toFixed(2)+"%"),1)])),_:2},1024)):(r(),c(y,{key:1,class:"font-12-green"},{default:u((()=>[N(A((Math.round(100*t.t4)/100).toFixed(2)+"%"),1)])),_:2},1024))])),_:2},1024),q(y,{class:"list-item-col text-align-right"},{default:u((()=>[q(y,{class:"font-14"},{default:u((()=>[N(A(t.t5),1)])),_:2},1024),t.t4>0?(r(),c(y,{key:0,class:"font-12-red"},{default:u((()=>[N(A(t.t6),1)])),_:2},1024)):(r(),c(y,{key:1,class:"font-12-green"},{default:u((()=>[N(A(t.t6),1)])),_:2},1024))])),_:2},1024)])),_:2},1024)))),128))])),_:1})])),_:1},8,["class","loading"])])),_:1},512),[[I,2===vt.value]])])),_:1})],64)}}},[["__scopeId","data-v-97f78ddc"]]);export{_t as default};
