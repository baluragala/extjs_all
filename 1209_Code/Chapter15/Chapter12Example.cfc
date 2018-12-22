<!---
 // ***************************************************************************************
 //		CLASS|TEMPLATE:
 //		Chapter_12\Chapter12Example.cfc
 //
 //		PURPOSE:
 //		A ColdFusion component used for Ajax examples in Chapter 12
 //
 //		AUTHOR:
 //		Stephen G. 'Cutter' Blades, Jr.
 //
 //		CHANGE LOG:
 // ***************************************************************************************
 //		SGB [07.17.08]
 //		Template created
 // ***************************************************************************************
 --->
<cfcomponent output="false">
	<!---
	 /	METHOD: example2
	 /
	 /	@param	id:numeric
	 /	@return	output:string
	 --->
	<cffunction name="example2" access="remote" output="false" returntype="string">
		<cfargument name="id" type="numeric" required="true" />
		<cfset var output = "" />
		<cfset var q = "" />

		<cftry>
			<!--- <cfquery name="q" datasource="chapter12">
				SELECT	firstName,
						lastName,
						occupation
				FROM	People
				WHERE	ID = <cfqueryparam cfsqltype="cf_sql_integer" value="#ARGUMENTS.id#" />
			</cfquery> --->
			<!---
			 * Since we don't have a database we'll fake it by creating a query object
			 --->
			 <cfscript>
				q = QueryNew("ID,firstName,lastName,occupation");
				QueryAddRow(q,1);
				QuerySetCell(q,"ID",1,1);
				QuerySetCell(q,"firstName","William",1);
				QuerySetCell(q,"lastName","Shakespeare",1);
				QuerySetCell(q,"occupation","National Poet",1);
			</cfscript>
			<cfcatch type="database">
				<!--- Place Error Handling Here --->
			</cfcatch>
		</cftry>

		<cfif IsDefined("q.recordcount") and q.recordcount>
			<cfsavecontent variable="output"><cfoutput>
				<b>#q.firstName# #q.lastName#</b>: <i>#q.occupation#</i><br />
			</cfoutput></cfsavecontent>
		</cfif>
		<cfreturn output />
	</cffunction>

	<!---
	 /	METHOD: getFileInfoByPath
	 /
	 /	@param	startPath:string
	 /	@return	q:query
	 --->
	<cffunction name="getFileInfoByPath" access="remote" output="false" returntype="query">
		<cfargument name="startPath" required="true" type="string" />
		<cfset var q = "" />
		<cftry>
			<cfdirectory action="list" name="q" directory="#ExpandPath(ARGUMENTS.startPath)#" filter="*.jpg" />
			<cfcatch type="any">
				<!--- Place Error Handler Here --->
				<cfset q = QueryNew('ID') />
				<cfset q = QuerySetCell(q,'ID',0,1) />
			</cfcatch>
		</cftry>
		<cfreturn q />
	</cffunction>

	<!---
	 /	METHOD: getDirectoryContents
	 /
	 /	@param	startPath:string
	 /	@param	recurse:boolean (optional)
	 /	@param	fileFilter:string (optional)
	 /	@param	dirFilter:string (optional - File|Dir)
	 /	@param	sortField:string (optional - NAME|SIZE|TYPE|DATELASTMODIFIED|ATTRIBUTES|MODE|DIRECTORY)
	 /	@param	sortDirection:string (option - ASC|DESC [defaults to ASC])
	 /	@return	retQ:query
	 --->
	<cffunction name="getDirectoryContents" access="remote" output="false" returntype="query">
		<cfargument name="startPath" required="true" type="string" />
		<cfargument name="recurse" required="false" type="boolean" default="false" />
		<cfargument name="sortDirection" required="false" type="string" default="ASC" />
		<!--- Set some function local variables --->
		<cfset var q = "" />
		<cfset var retQ = "" />
		<cfset var attrArgs = {} />
		<cfset var ourDir = ExpandPath(ARGUMENTS.startPath) />
		<!--- Create some lists of valid arguments --->
		<cfset var filterList = "File,Dir" />
		<cfset var sortDirList = "ASC,DESC" />
		<cfset var columnList = "NAME,SIZE,TYPE,DATELASTMODIFIED,ATTRIBUTES,MODE,DIRECTORY" />
		<cftry>
			<cfset attrArgs.recurse = ARGUMENTS.recurse />
			<!--- Verify the directory exists before continuing --->
			<cfif DirectoryExists(ourDir)>
				<cfset attrArgs.directory = ourDir />
			<cfelse>
				<cfthrow type="Custom" errorcode="Our_Custom_Error" message="The directory you are trying to reach does not exist." />
			</cfif>
			<!--- Conditionally apply some optional filtering and sorting --->
			<cfif IsDefined("ARGUMENTS.fileFilter")>
				<cfset attrArgs.filter = ARGUMENTS.fileFilter />
			</cfif>
			<cfif IsDefined("ARGUMENTS.sortField")>
				<cfif ListFindNoCase(columnList,ARGUMENTS.sortField)>
					<cfset attrArgs.sort = ARGUMENTS.sortField & " " & ARGUMENTS.sortDirection />
				<cfelse>
					<cfthrow type="custom" errorcode="Our_Custom_Error" message="You have chosen an invalid sort field. Please use one of the following: #columnList#" />
				</cfif>
			</cfif>
			<cfdirectory action="list" name="q" attributeCollection="#attrArgs#" />
			<!--- If there are files and/or folders, and you want to sort by TYPE --->
			<cfif q.recordcount and IsDefined("ARGUMENTS.dirFilter")>
				<cfif ListFindNoCase(filterList,ARGUMENTS.dirFilter)>
					<cfquery name="retQ" dbtype="query">
						SELECT	#columnList#
						FROM	q
						WHERE	TYPE = '#ARGUMENTS.dirFilter#'
					</cfquery>
				<cfelse>
					<cfthrow type="Custom" errorcode="Our_Custom_Error" message="You have passed an invalid dirFilter. The only accepted values ar File and Dir." />
				</cfif>
			<cfelse>
				<cfset retQ = q />
			</cfif>
			<cfcatch type="any">
				<!--- Place Error Handler Here --->
			</cfcatch>
		</cftry>
		<cfreturn retQ />
	</cffunction>
</cfcomponent>