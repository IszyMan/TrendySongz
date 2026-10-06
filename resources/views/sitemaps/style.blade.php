<xsl:stylesheet
    version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:sm="http://www.sitemaps.org/schemas/sitemap/0.9"
    exclude-result-prefixes="sm"
>
    <xsl:output
        method="html"
        encoding="UTF-8"
        indent="yes"
    />

    <xsl:template match="/">
        <html lang="en">
            <head>
                <title>XML Sitemap — TrendySongz</title>

                <meta
                    name="viewport"
                    content="width=device-width, initial-scale=1"
                />

                <style>
                    body {
                        margin: 0;
                        background: #f5f7f6;
                        color: #26332b;
                        font-family: Arial, sans-serif;
                        font-size: 15px;
                        line-height: 1.6;
                    }

                    .sitemap-header {
                        padding: 25px;
                        background: #101713;
                        color: #ffffff;
                    }

                    .sitemap-header-inner,
                    .sitemap-content {
                        max-width: 1100px;
                        margin: 0 auto;
                    }

                    .sitemap-brand {
                        color: olive;
                        font-size: 24px;
                        font-weight: bold;
                        text-decoration: none;
                    }

                    .sitemap-content {
                        padding: 30px 20px;
                    }

                    h1 {
                        margin: 0 0 12px;
                        font-size: 30px;
                    }

                    p {
                        margin: 0 0 16px;
                    }

                    a {
                        color: #16803d;
                    }

                    .sitemap-table-wrap {
                        overflow-x: auto;
                        margin-top: 24px;
                        border: 1px solid #dce5df;
                        border-radius: 6px;
                        background: #ffffff;
                    }

                    table {
                        width: 100%;
                        border-collapse: collapse;
                        text-align: left;
                    }

                    th,
                    td {
                        padding: 12px 16px;
                        border-bottom: 1px solid #e7ece9;
                        vertical-align: top;
                    }

                    th {
                        background: #eaf4ed;
                        font-weight: bold;
                    }

                    tbody tr:nth-child(even) {
                        background: #fafcfb;
                    }

                    tbody tr:last-child td {
                        border-bottom: 0;
                    }

                    .sitemap-modified {
                        width: 230px;
                        white-space: nowrap;
                        color: #66736b;
                    }

                    td a {
                        overflow-wrap: anywhere;
                    }
                </style>
            </head>

            <body>
                <header class="sitemap-header">
                    <div class="sitemap-header-inner">
                        <a href="/" class="sitemap-brand">
                            TrendySongz
                        </a>
                    </div>
                </header>

                <main class="sitemap-content">
                    <h1>XML Sitemap</h1>

                    <p>
                        This XML sitemap is generated for TrendySongz
                        to help search engines discover published pages.
                    </p>

                    <xsl:choose>
                        <xsl:when test="sm:sitemapindex">
                            <p>
                                This sitemap index contains
                                <strong>
                                    <xsl:value-of
                                        select="count(sm:sitemapindex/sm:sitemap)"
                                    />
                                </strong>
                                sitemap files.
                            </p>
                        </xsl:when>

                        <xsl:otherwise>
                            <p>
                                This sitemap contains
                                <strong>
                                    <xsl:value-of
                                        select="count(sm:urlset/sm:url)"
                                    />
                                </strong>
                                URLs.
                            </p>

                            <p>
                                <a href="/sitemaps.xml">
                                    Return to the sitemap index
                                </a>
                            </p>
                        </xsl:otherwise>
                    </xsl:choose>

                    <div class="sitemap-table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>
                                        <xsl:choose>
                                            <xsl:when test="sm:sitemapindex">
                                                Sitemap URL
                                            </xsl:when>

                                            <xsl:otherwise>
                                                Page URL
                                            </xsl:otherwise>
                                        </xsl:choose>
                                    </th>

                                    <th class="sitemap-modified">
                                        Last Modified
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                <xsl:for-each
                                    select="sm:sitemapindex/sm:sitemap | sm:urlset/sm:url"
                                >
                                    <tr>
                                        <td>
                                            <a href="{sm:loc}">
                                                <xsl:value-of select="sm:loc" />
                                            </a>
                                        </td>

                                        <td class="sitemap-modified">
                                            <xsl:choose>
                                                <xsl:when test="sm:lastmod">
                                                    <xsl:value-of select="sm:lastmod" />
                                                </xsl:when>

                                                <xsl:otherwise>
                                                    —
                                                </xsl:otherwise>
                                            </xsl:choose>
                                        </td>
                                    </tr>
                                </xsl:for-each>
                            </tbody>
                        </table>
                    </div>
                </main>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>