import{w as t,aj as a,W as e,X as s,r as i,x as l,y as o,z as n,B as r,C as d,H as h,L as m,M as u,D as p,E as c,F as g,G as y,I as x,J as f,N as _}from"./index-DugkB_-4.js";import{_ as b}from"./u-tabs.DCj_ugoh.js";import{r as w}from"./uni-app.es.CX-CC-lz.js";import{_ as k}from"./u-sticky.Bk6Q6bsK.js";import{_ as K}from"./u-loadmore.DoeN8jY1.js";import{_ as j}from"./u-empty.CQpJXShg.js";import{D as C}from"./index.CCikdCXX.js";import{_ as D}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./u-line.BpwwmqXn.js";import"./u-loading-icon.CCS9DSWM.js";import"./u-icon.Ca1c56qc.js";const N=D({data(){return{title:"Hello",list2:[{name:this.$t("page.title.hotNews")},{name:this.$t("page.title.news")}],tabKey:0,news:[],isNext:!0,loadStatus:"loadmore",params:{page:1,pageSize:10,type:0}}},onLoad(a){this.tabKey=a.origin??0,this.tabKey=1*this.tabKey,this.params.type=this.tabKey?2:this.tabKey,t({title:this.list2[this.tabKey].name})},onPullDownRefresh(){setTimeout((function(){a()}),1e3),this.isNext=!0,this.params.page=1,this.news=[],this.getData()},onReachBottom(){this.isNext&&(this.params.page+=1,this.getData())},mounted(){this.getData()},methods:{handleChangeTabkey({index:a}){this.tabKey=a,t({title:this.list2[this.tabKey].name}),this.news=[],this.params.page=1,this.isNext=!0,this.params.type=this.tabKey?2:this.tabKey,this.getData()},getData(){e({title:this.$t("common.text.requesting"),mask:!0}),C(this.params).then((t=>{console.log(t.data.data),this.isNext=t.data.data.length,this.news=this.news.concat(t.data.data??[]),this.loadStatus=this.isNext?"loadmore":"nomore"})).finally((()=>{s()}))},handleJumpe(t){0==this.tabKey?i({url:`/pages/article-detail/article-detail?id=${t}`}):i({url:`/pages/article-detail/article-detail?id=${t}&type=2`})},handleNext(){this.isNext&&(this.params.page+=1,this.getData())}}},[["render",function(t,a,e,s,i,C){const D=w(l("up-tabs"),b),N=w(l("up-sticky"),k),$=r,T=_,J=w(l("up-loadmore"),K),S=w(l("up-empty"),j);return d(),o($,{class:"news-page"},{default:n((()=>[h(N,{zIndex:"999",style:{top:"0"},"bg-color":"#fff"},{default:n((()=>[h(D,{list:i.list2,current:i.tabKey,lineColor:"red",onChange:C.handleChangeTabkey,style:{width:"220upx",margin:"0 auto"}},null,8,["list","current","onChange"])])),_:1}),m(h($,{class:"news-content-box"},{default:n((()=>[(d(!0),p(g,null,c(i.news,((t,a)=>(d(),o($,{class:"list",key:a},{default:n((()=>[h($,{onClick:()=>{C.handleJumpe(t.id)}},{default:n((()=>[h($,{class:"time"},{default:n((()=>[x(f(t.create_time),1)])),_:2},1024),h($,{class:"info"},{default:n((()=>[x(f(t.title),1)])),_:2},1024)])),_:2},1032,["onClick"]),h(T,{mode:"scaleToFill",style:{width:"260upx","max-height":"130upx"},src:t.cj_thumbs},null,8,["src"])])),_:2},1024)))),128))])),_:1},512),[[u,!i.tabKey]]),m(h($,{class:"market-list-box"},{default:n((()=>[(d(!0),p(g,null,c(i.news,((t,a)=>(d(),o($,{class:"list-box",key:a,onClick:()=>{C.handleJumpe(t.id)}},{default:n((()=>[h($,null,{default:n((()=>[h($,{class:"icon-box"})])),_:1}),h($,null,{default:n((()=>[h($,{class:"time"},{default:n((()=>[x(f(t.update_time),1)])),_:2},1024),h($,null,{default:n((()=>[x(f(t.rich_text),1)])),_:2},1024)])),_:2},1024)])),_:2},1032,["onClick"])))),128))])),_:1},512),[[u,i.tabKey]]),h(J,{line:"",status:i.loadStatus,onClick:C.handleNext,"loadmore-text":t.$t("common.text.clickToLoadMore")},null,8,["status","onClick","loadmore-text"]),i.news.length?y("",!0):(d(),o(S,{key:0,mode:"data",width:"160","margin-top":"450rpx",icon:"/static/Vertretung/noMoreData@2x.png"}))])),_:1})}],["__scopeId","data-v-eca18a28"]]);export{N as default};