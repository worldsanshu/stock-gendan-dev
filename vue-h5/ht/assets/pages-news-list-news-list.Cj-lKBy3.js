import{w as t,ai as a,W as e,X as s,r as i,x as l,y as o,z as n,A as r,C as d,H as h,L as u,M as m,D as p,E as c,F as g,G as y,I as x,J as f,N as b}from"./index-egBWsG2I.js";import{_}from"./u-tabs.DyDIhk4q.js";import{r as w}from"./uni-app.es.Ch6kz4fv.js";import{_ as k}from"./u-sticky.COvHZZ0y.js";import{_ as K}from"./u-loadmore.CxyjvHxa.js";import{_ as j}from"./u-empty.bv5pQEpI.js";import{D as C}from"./index.DY_m6FRZ.js";import{_ as N}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./u-line.DSpafCyq.js";import"./u-loading-icon.CJLKev2m.js";import"./u-icon.CVwHtxQg.js";const D=N({data(){return{title:"Hello",list2:[{name:this.$t("page.title.hotNews")},{name:this.$t("page.title.news")}],tabKey:0,news:[],isNext:!0,loadStatus:"loadmore",params:{page:1,pageSize:10,type:0}}},onLoad(a){this.tabKey=a.origin??0,this.tabKey=1*this.tabKey,this.params.type=this.tabKey?2:this.tabKey,t({title:this.list2[this.tabKey].name})},onPullDownRefresh(){setTimeout((function(){a()}),1e3),this.isNext=!0,this.params.page=1,this.news=[],this.getData()},onReachBottom(){this.isNext&&(this.params.page+=1,this.getData())},mounted(){this.getData()},methods:{handleChangeTabkey({index:a}){this.tabKey=a,t({title:this.list2[this.tabKey].name}),this.news=[],this.params.page=1,this.isNext=!0,this.params.type=this.tabKey?2:this.tabKey,this.getData()},getData(){e({title:this.$t("common.text.requesting")}),C(this.params).then((t=>{console.log(t.data.data),this.isNext=t.data.data.length,this.news=this.news.concat(t.data.data??[]),this.loadStatus=this.isNext?"loadmore":"nomore"})).finally((()=>{s()}))},handleJumpe(t){0==this.tabKey?i({url:`/pages/article-detail/article-detail?id=${t}`}):i({url:`/pages/article-detail/article-detail?id=${t}&type=2`})},handleNext(){this.isNext&&(this.params.page+=1,this.getData())}}},[["render",function(t,a,e,s,i,C){const N=w(l("up-tabs"),_),D=w(l("up-sticky"),k),T=d,$=b,S=w(l("up-loadmore"),K),J=w(l("up-empty"),j);return o(),n(T,{class:"news-page"},{default:r((()=>[h(D,{zIndex:"999",style:{top:"0"},"bg-color":"#fff"},{default:r((()=>[h(N,{list:i.list2,current:i.tabKey,lineColor:"red",onChange:C.handleChangeTabkey,style:{width:"220upx",margin:"0 auto"}},null,8,["list","current","onChange"])])),_:1}),u(h(T,{class:"news-content-box"},{default:r((()=>[(o(!0),p(g,null,c(i.news,((t,a)=>(o(),n(T,{class:"list",key:a},{default:r((()=>[h(T,{onClick:()=>{C.handleJumpe(t.id)}},{default:r((()=>[h(T,{class:"time"},{default:r((()=>[x(f(t.create_time),1)])),_:2},1024),h(T,{class:"info"},{default:r((()=>[x(f(t.title),1)])),_:2},1024)])),_:2},1032,["onClick"]),h($,{mode:"scaleToFill",style:{width:"260upx","max-height":"130upx"},src:t.cj_thumbs},null,8,["src"])])),_:2},1024)))),128))])),_:1},512),[[m,!i.tabKey]]),u(h(T,{class:"market-list-box"},{default:r((()=>[(o(!0),p(g,null,c(i.news,((t,a)=>(o(),n(T,{class:"list-box",key:a,onClick:()=>{C.handleJumpe(t.id)}},{default:r((()=>[h(T,null,{default:r((()=>[h(T,{class:"icon-box"})])),_:1}),h(T,null,{default:r((()=>[h(T,{class:"time"},{default:r((()=>[x(f(t.update_time),1)])),_:2},1024),h(T,null,{default:r((()=>[x(f(t.rich_text),1)])),_:2},1024)])),_:2},1024)])),_:2},1032,["onClick"])))),128))])),_:1},512),[[m,i.tabKey]]),h(S,{line:"",status:i.loadStatus,onClick:C.handleNext,"loadmore-text":t.$t("common.text.clickToLoadMore")},null,8,["status","onClick","loadmore-text"]),i.news.length?y("",!0):(o(),n(J,{key:0,mode:"data",width:"160","margin-top":"450rpx",icon:"/static/Vertretung/noMoreData@2x.png"}))])),_:1})}],["__scopeId","data-v-1893a587"]]);export{D as default};
