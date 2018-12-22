<!---
 // ***************************************************************************************
 //		CLASS|TEMPLATE:
 //		/Application.cfc
 //
 //		PURPOSE:
 //		A simple Media Asset Management Application using ExtJs 2.1
 //
 //		COPYRIGHT:
 //		Stephen G. 'Cutter' Blades, Jr. (c) 2008
 //
 //		Ext COPYRIGHT:
 //		Ext JS Library 2.1
 //		Copyright(c) 2006-2008, Ext JS, LLC.
 //		licensing@extjs.com
 //
 //		http://extjs.com/license
 //
 //		LICENSE:
 //		TBD
 //
 //		CHANGE LOG:
 // ***************************************************************************************
 //		SGB [06.17.08]
 //		Template created
 // ***************************************************************************************
 --->

<cfcomponent output="true">
	<cfset this.name = "ExtBook" />
	<cfset this.applicationTimeout = createTimeSpan(0,2,0,0) />
	<cfset this.sessionManagement = true />
	<cfset this.sessionTimeout = createTimeSpan(0,0,20,0) />
	<cfset this.setClientCookies = true />

	<cffunction name="onApplicationStart" returnType="boolean" output="true">
		<!---
		 *	Use ColdSpring to handle dependencies, as well as create our
		 *	remote proxy objects, if they don't already exist

		<cfset var configPath = expandPath("/config/services.xml") />
		<cfset var LOCAL = {} />
		<cfset var csConfigProps = {dsn = "presoBuilder"} />
		<cfscript>
			if(!StructKeyExists(APPLICATION,"csBeanFactory")){
				APPLICATION.csBeanFactory = createObject("component","coldspring.beans.DefaultXMLBeanFactory").Init(defaultProperties=csConfigProps);
				APPLICATION.csBeanFactory.loadBeans(configPath,true);
				LOCAL.arrBeans = StructKeyArray(APPLICATION.csBeanFactory.GetBeanDefinitionList());
				for(LOCAL.i=1;LOCAL.i<=ArrayLen(LOCAL.arrBeans);LOCAL.i++){
					if(Find("Remote_",LOCAL.arrBeans[LOCAL.i])){
						LOCAL.tmpObj = APPLICATION.csBeanFactory.getBean("&" & LOCAL.arrBeans[LOCAL.i]);
						if(!LOCAL.tmpObj.isConstructed()){
							LOCAL.tmpObj.createRemoteProxy();
						}
					}
				}
			}
		</cfscript> --->
		<cfreturn true />
	</cffunction>

	<cffunction name="onApplicationEnd" returnType="void" output="false">
		<cfargument name="applicationScope" required="true" />
	</cffunction>

	<cffunction name="onRequestStart" returnType="boolean" output="true">
		<cfargument name="thePage" type="string" required="true" />
		<!--- <cfif not IsDefined("SESSION.dsn")>
			<cfset SESSION.dsn = "bblades_ds1" />
		</cfif> --->
		<cfscript>
			if(IsDefined("URL.reinit")){
				StructClear(APPLICATION);
				StructClear(SESSION);
				onApplicationStart();
			}
		</cfscript>
		<cfreturn true />
	</cffunction>

	<!---
	 *	Can not use with Ajax based applications
	<cffunction name="onRequest" returnType="void">
		<cfargument name="thePage" type="string" required="true" />
		<cfinclude template="#arguments.thePage#" />
	</cffunction>
	 --->

	<cffunction name="onRequestEnd" returnType="void" output="false">
	<cfargument name="thePage" type="string" required="true" />
	</cffunction>

	<!---
	 *	Temporarily disabled for faster initial application debugging
	<cffunction name="onError" returnType="void" output="true">
		<cfargument name="exception" required="true" />
		<cfargument name="eventname" type="string" required="true" />
		<cfdump expand="true" var="#ARGUMENTS#" />
	</cffunction>
	 --->

	<cffunction name="onSessionStart" returnType="void" output="false">
	</cffunction>

	<cffunction name="onSessionEnd" returnType="void" output="false">
		<cfargument name="sessionScope" type="struct" required="true" />
		<cfargument name="appScope" type="struct" required="false" />
	</cffunction>
</cfcomponent>