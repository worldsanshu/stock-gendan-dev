import{V as a,aj as t,x as e,y as s,D as r,H as l,Y as o,z as i,G as u,A as n,F as d,aq as p,C as m,I as c,N as _,O as f}from"./index-DxUBkcQO.js";import{_ as v}from"./uni-nav-bar.D3FG-aeM.js";import{c as j,d as g,r as y}from"./uni-app.es.WyEFFhdr.js";import{_ as h}from"./u-tabs.B6hA7FlC.js";import{_ as k}from"./u-search.3oO97G2p.js";import{u as b,_ as C}from"./use-star-investor.B9HuT2Ni.js";import{_ as x}from"./u-skeleton.CGJIjGk_.js";import{_ as w}from"./u-empty.B-uqFSHu.js";import{_ as V}from"./u-loadmore.BbrrZ_01.js";import{a as A,O as D}from"./index.B2Jj0BgA.js";import{_ as E}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./uni-icons.BDom0B1X.js";import"./uni-status-bar.DTIfAn29.js";import"./u-icon.BCEdui8G.js";import"./u-avatar.wl7-XNge.js";import"./u-text.BBexnxe8.js";import"./u-link.WvmVOVcL.js";import"./u-tag.CcGzLSJx.js";import"./u-transition.Dd8ALNVU.js";import"./use-data-loader.47SkjMx6.js";import"./u-line.BXTok7nE.js";import"./u-loading-icon.DIzsuW0j.js";const H=""+new URL("star-investor-bg-CkdrfHE4.png",import.meta.url).href,I=E({__name:"star-investor-v2",setup(E){let I=0;const U=a(""),B=a(""),{tabs:F,tabCurrent:N,list:O,loading:R,loadMore:S,loadStatus:q,noData:z,refresh:G}=b();function J(){p("/pages/record/record",{id:20})}return A().then((a=>{B.value=a.data.module})),j((async()=>{await G(),t()})),g(S),D({type:1,page:1}).then((a=>{0===a.data.list.data.length&&(I=1)})),G(),(a,t)=>{const p=y(e("uni-nav-bar"),v),j=y(e("up-tabs"),h),g=y(e("up-search"),k),b=m,A=y(e("fc-star-investor-list"),C),D=y(e("up-skeleton"),x),E=y(e("up-empty"),w),K=y(e("up-loadmore"),V),L=_,M=f;return s(),r(d,null,[l(p,{title:"明星投资者","status-bar":"","background-color":"#EB5528",color:"#fff","right-text":"优投记录",fixed:"",border:!1,onClickRight:J}),B.value&&0===B.value.trader_list_type&&0===o(I)?(s(),i(j,{key:0,list:o(F),scrollable:!1,current:o(N),onChange:t[0]||(t[0]=a=>N.value=a.index)},null,8,["list","current"])):u("",!0),B.value&&1===B.value.trader_list_type?(s(),i(b,{key:1,class:"sear"},{default:n((()=>[l(g,{placeholder:"输入导师名称",modelValue:U.value,"onUpdate:modelValue":t[1]||(t[1]=a=>U.value=a),onChange:o(G),onClear:o(G)},null,8,["modelValue","onChange","onClear"])])),_:1})):u("",!0),B.value&&0===B.value.trader_list_type||U.value?(s(),i(b,{key:2,class:"body p-30"},{default:n((()=>[l(D,{loading:o(R),rows:"5",title:!1,"rows-height":140,isArticle:!1},{default:n((()=>[l(A,{list:o(O)},null,8,["list"])])),_:1},8,["loading"]),o(z)?(s(),i(E,{key:0,mode:"data",icon:"/static/empty/empty-data.png"})):u("",!0),o(z)||o(R)?u("",!0):(s(),i(K,{key:1,status:o(q),onClick:o(S)},null,8,["status","onClick"]))])),_:1})):u("",!0),B.value&&1===B.value.trader_list_type&&!U.value?(s(),i(b,{key:3,class:"keyword-no-data"},{default:n((()=>[l(L,{src:H,mode:"widthFix",class:"img"}),l(b,{class:"title"},{default:n((()=>[c("跟买如何操作？")])),_:1}),l(M,null,{default:n((()=>[c("跟买十分简单，在搜索框中输入您想跟买的明星导师名称，")])),_:1}),l(M,null,{default:n((()=>[c("就可以查看到明星导师的详细信息，")])),_:1}),l(M,null,{default:n((()=>[c("点击头像就可以进入申请页面操作。")])),_:1}),l(M,{style:{"margin-top":"50rpx"}},{default:n((()=>[c("只有在您每天确认跟买后，操盘师才会去操盘您的跟买资金。")])),_:1}),l(M,{class:"red"},{default:n((()=>[c("请注意，若您确认跟买的时间太迟，有可能买不上当日的金股。")])),_:1})])),_:1})):u("",!0)],64)}}},[["__scopeId","data-v-4059e80a"]]);export{I as default};
