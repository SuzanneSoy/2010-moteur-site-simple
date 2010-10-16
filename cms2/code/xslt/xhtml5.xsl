<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
	<xsl:output method="xml" indent="no" encoding="utf-8"/>
	<xsl:template match="document">
		<html>
			<head>
				<title>
					<xsl:value-of select="header/title/text/@text"/>
				</title>
			</head>
			<body>
				<xsl:apply-templates select="erreurs"/>
				<xsl:apply-templates select="article"/>
			</body>
		</html>
	</xsl:template>
	
	<xsl:template match="article">
		<article>
			<xsl:apply-templates select="*"/>
		</article>
	</xsl:template>
	
	<xsl:template match="hX">
		<h1>
			<xsl:apply-templates select="*"/>
		</h1>
	</xsl:template>
	
	<xsl:template match="a">
		<a href="{@href}">
			<xsl:apply-templates select="*"/>
		</a>
	</xsl:template>
	
	<xsl:template match="input_text_line">
		<input type="text" value="{@value}"/>
	</xsl:template>
	
	<xsl:template match="input_submit">
		<input type="submit" value="{@label}"/>
	</xsl:template>
	
	<xsl:template match="form">
		<form action="{@action}">
			<xsl:apply-templates select="*"/>
		</form>
	</xsl:template>
	
	<xsl:template match="span">
		<!-- TODO : mettre le class="?" ssi c'est non vide. -->
		<span class="{@class}">
			<xsl:apply-templates select="*"/>
		</span>
	</xsl:template>
	
	<xsl:template match="p|ul|li">
		<xsl:element name="{local-name()}">
			<xsl:apply-templates select="*"/>
		</xsl:element>
	</xsl:template>
	
	<xsl:template match="text">
		<xsl:value-of select="@text"/>
	</xsl:template>
	
	<xsl:template match="erreurs">
		<xsl:apply-templates select="*" mode="copy"/>
	</xsl:template>
	
	<xsl:template match="@*|*|text()" mode="copy">
		<xsl:copy>
			<xsl:apply-templates select="@*|*|text()" mode="copy"/>
		</xsl:copy>
	</xsl:template>
</xsl:stylesheet>
