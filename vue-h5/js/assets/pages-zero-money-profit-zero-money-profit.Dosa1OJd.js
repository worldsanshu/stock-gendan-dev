import{$ as t,x as a,y as s,z as i,A as e,C as l,H as o,I as n,J as r,D as d,E as h,F as f,L as u,M as p,O as c,B as _}from"./index-B_R0yMjY.js";import{_ as m}from"./u-icon.C-x-hKK7.js";import{r as b}from"./uni-app.es.BJ_eOOZE.js";import{_ as x}from"./l-echart.6RTmJnyJ.js";import{i as y,U as g}from"./index.BiBHJe1A.js";import{e as L}from"./index.bMs3AERB.js";import{_ as C}from"./_plugin-vue_export-helper.BCo6x5W8.js";const K=C({data:()=>({page:1,tabKey:0,id:1,allList:"",tabs:["七日年化","万份收益"],options:{grid:{left:"6%",right:"8%",top:"10%",containLabel:!0},dataZoom:[{type:"inside",start:0,end:100},{start:0,end:100}],xAxis:{type:"category",boundaryGap:!1,data:[],axisLabel:{fontSize:12,color:"#bbb"},axisTick:{show:!1},axisLine:{show:!1}},yAxis:{type:"value",axisLabel:{fontSize:12,formatter:"{value}%",color:"#bbb"}},series:[{data:[],type:"line",smooth:!0,showSymbol:!1,areaStyle:{color:"#fbdbe1"},lineStyle:{color:"#ea6a6d",width:2,type:"solid"}}]}}),mounted(){this.estStatisti(),y({}).then((t=>{this.allList=t.data}))},methods:{handleJumpBack(){t()},estStatisti(){g({page:this.page,interest_record_id:this.id}).then((t=>{this.options.xAxis.data;for(var a=0;a<t.data.length;a++)this.options.xAxis.data.push(t.data[a].date),this.options.series[0].data.push(t.data[a].rate),0==this.tabKey?this.init():this.init1()}))},handleChangeTabkey(t){this.tabKey=t;({0:()=>{this.id=1,this.options.xAxis.data=[],this.options.series[0].data=[],this.estStatisti(),this.init()},1:()=>{this.id=2,this.options.yAxis.axisLabel.formatter="{value}",this.options.xAxis.data=[],this.options.series[0].data=[],this.estStatisti(),this.init1()}})[this.tabKey]()},async init(){(await this.$refs.monthKChartRef.init(L)).setOption(this.options)},async init1(){(await this.$refs.monthKChartRef1.init(L)).setOption(this.options)}}},[["render",function(t,y,g,L,C,K){const k=b(a("up-icon"),m),S=l,w=c,A=b(a("l-echart"),x);return s(),i(S,{class:"zero-money-profit-page"},{default:e((()=>[o(S,{class:"header-box"},{default:e((()=>[o(S,{onClick:K.handleJumpBack},{default:e((()=>[o(k,{name:"arrow-left",size:"38rpx",color:"#26292D",style:{"margin-right":"12rpx"}})])),_:1},8,["onClick"]),o(S,{class:"header-infos"},{default:e((()=>[o(S,null,{default:e((()=>[n("当前购买产品")])),_:1}),o(w,null,{default:e((()=>[n(r(C.allList.interest_basic_name),1)])),_:1})])),_:1})])),_:1}),o(S,{class:"zero-profit-conent"},{default:e((()=>[o(S,{class:"list"},{default:e((()=>[o(S,null,{default:e((()=>[n("7日年化")])),_:1}),o(w,null,{default:e((()=>[n(r(C.allList.list?C.allList.list[0].interest_rate:0)+"%",1)])),_:1})])),_:1}),o(S,{class:"list"},{default:e((()=>[o(S,null,{default:e((()=>[n("万份收益（元）")])),_:1}),o(w,null,{default:e((()=>[n(r(C.allList.list?C.allList.list[1].interest_rate:0),1)])),_:1})])),_:1})])),_:1}),o(S,{class:"echarts-box"},{default:e((()=>[o(S,{class:"tabs-box"},{default:e((()=>[(s(!0),d(f,null,h(C.tabs,((t,a)=>(s(),i(S,{class:_([C.tabKey===a?"on":""]),key:a,onClick:()=>{K.handleChangeTabkey(a)}},{default:e((()=>[n(r(t),1)])),_:2},1032,["class","onClick"])))),128))])),_:1}),u(o(S,null,{default:e((()=>[o(S,{style:{width:"100%",height:"750rpx"}},{default:e((()=>[o(A,{ref:"monthKChartRef",onFinished:K.init},null,8,["onFinished"])])),_:1})])),_:1},512),[[p,!C.tabKey]]),u(o(S,null,{default:e((()=>[o(S,{style:{width:"100%",height:"750rpx"}},{default:e((()=>[o(A,{ref:"monthKChartRef1",onFinished:K.init},null,8,["onFinished"])])),_:1})])),_:1},512),[[p,C.tabKey]])])),_:1}),o(S,{class:"zero-profit-footer"},{default:e((()=>[o(S,null,{default:e((()=>[n("产品过往业绩不预示未来表现，相关数据仅供多考，不构成投资建议，市场有风险，理财需谨慎")])),_:1})])),_:1})])),_:1})}],["__scopeId","data-v-5f5f5131"]]);export{K as default};
