import{x as t,y as s,z as a,A as e,C as i,H as l,I as o,J as n,D as r,E as u,F as p,G as d}from"./index-B_R0yMjY.js";import{_ as m}from"./u-loadmore.DxA8d6wH.js";import{r as c}from"./uni-app.es.BJ_eOOZE.js";import{_ as h}from"./u-empty._OicmcJQ.js";import{W as _,i as f}from"./index.BiBHJe1A.js";import{_ as g}from"./_plugin-vue_export-helper.BCo6x5W8.js";import"./u-line.D4YZ5jxS.js";import"./u-loading-icon.BSolyre8.js";import"./u-icon.C-x-hKK7.js";const x=g({data:()=>({status:"loadmore",allList:"",Statistics:"",provinces:[],page:1,last_page:0}),methods:{teres(){this.status="loading",_({page:this.page}).then((t=>{this.page>t.data.list.list.last_page?this.status="loadmore":this.status="nomore";for(var s=0;s<t.data.list.list.data.length;s++)this.provinces.push(t.data.list.list.data[s]);this.Statistics=t.data.list.list,this.last_page=t.data.list.list.last_page}))},onReachBottom(){this.page>this.last_page?(this.page++,this.teres()):this.status="nomore"}},mounted(){f({}).then((t=>{this.allList=t.data})),this.teres()}},[["render",function(_,f,g,x,j,v){const y=i,k=c(t("up-loadmore"),m),L=c(t("up-empty"),h);return s(),a(y,null,{default:e((()=>[l(y,{class:"head"},{default:e((()=>[l(y,{class:"munsfw"},{default:e((()=>[o(" 累积收益 ")])),_:1}),l(y,{class:"munt"},{default:e((()=>[l(y,{class:"muntlse"},{default:e((()=>[o(" ￥ ")])),_:1}),l(y,{class:"muntrse"},{default:e((()=>[o(n(j.allList.total_interest),1)])),_:1})])),_:1}),l(y,{class:"munsfw"},{default:e((()=>[o(" 昨日 ￥"+n(j.allList.yesterday_interest),1)])),_:1})])),_:1}),(s(!0),r(p,null,u(j.provinces,((t,i)=>(s(),a(y,{class:"list",key:i},{default:e((()=>[l(y,{class:"left-ic"},{default:e((()=>[o(n(t.rebate_time_text),1)])),_:2},1024),l(y,{class:"left-ic"},{default:e((()=>[o(" 收益￥"+n(t.interest),1)])),_:2},1024)])),_:2},1024)))),128)),j.provinces.length>0?(s(),a(k,{key:0,status:j.status,onClick:v.onReachBottom},null,8,["status","onClick"])):d("",!0),0==j.provinces.length?(s(),a(L,{key:1,mode:"data",width:"200","margin-top":"220rpx",icon:"/static/Vertretung/noMoreData@2x.png",text:_.$t("common.text.noData")},null,8,["text"])):d("",!0)])),_:1})}],["__scopeId","data-v-574fe2d3"]]);export{x as default};
