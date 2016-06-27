#
# Table structure for table `wfdownloads_broken`
#

CREATE TABLE wfdownloads_broken (
    reportid                    int(5) NOT NULL auto_increment,
    lid                         int(11) NOT NULL default '0',
    sender                      int(11) NOT NULL default '0',
    ip                          varchar(20) NOT NULL default '',
    date                        varchar(11) NOT NULL default '0',
    confirmed                   tinyint(1) NOT NULL default '0',
    acknowledged                tinyint(1) NOT NULL default '0',
    PRIMARY KEY (reportid),
    KEY lid (lid),
    KEY sender (sender),
    KEY ip (ip)
) ENGINE=MyISAM;

#
# Dumping data for table `wfdownloads_broken`
#


# --------------------------------------------------------

#
# Table structure for table `wfdownloads_cat`
#

CREATE TABLE wfdownloads_cat (
    cid                         int(5) unsigned NOT NULL auto_increment,
    pid                         int(5) unsigned NOT NULL default '0',
    title                       varchar(255) NOT NULL default '',
    imgurl                      varchar(255) NOT NULL default '',
    description                 text NOT NULL,
    total                       int(11) NOT NULL default '0',
    summary                     text NOT NULL,
    spotlighttop                int(11) NOT NULL default '0',
    spotlighthis                int(11) NOT NULL default '0',
    dohtml                      tinyint(1) NOT NULL default '0',
    dosmiley                    tinyint(1) NOT NULL default '1',
    doxcode                     tinyint(1) NOT NULL default '1',
    doimage                     tinyint(1) NOT NULL default '1',
    dobr                        tinyint(1) NOT NULL default '1',
    weight                      int(11) NOT NULL default '0',
    formulize_fid               int(5) NOT NULL default '0',
    PRIMARY KEY (cid),
    KEY pid (pid)
) ENGINE=MyISAM;

#
# Dumping data for table `wfdownloads_cat`
#

# --------------------------------------------------------

#
# Table structure for table `wfdownloads_downloads`
#

CREATE TABLE wfdownloads_downloads (
    lid                         int(11) unsigned NOT NULL auto_increment,
    cid                         int(5) unsigned NOT NULL default '0',
    title                       varchar(255) NOT NULL default '',
    url                         varchar(255) NOT NULL default '',
    filename                    varchar(150) NOT NULL default '',
    filetype                    varchar(100) NOT NULL default '',
    homepage                    varchar(100) NOT NULL default '',
    version                     varchar(20) NOT NULL default '',
    size                        int(8) NOT NULL default '0',
    platform                    varchar(50) NOT NULL default '',
    screenshot                  varchar(255) NOT NULL default '',
    screenshot2                 varchar(255) NOT NULL default '',
    screenshot3                 varchar(255) NOT NULL default '',
    screenshot4                 varchar(255) NOT NULL default '',
    submitter                   int(11) NOT NULL default '0',
    publisher                   varchar(255) NOT NULL default '',
    status                      tinyint(2) NOT NULL default '0',
    date                        int(10) NOT NULL default '0',
    hits                        int(11) unsigned NOT NULL default '0',
    rating                      double(6,4) NOT NULL default '0.0000',
    votes                       int(11) unsigned NOT NULL default '0',
    comments                    int(11) unsigned NOT NULL default '0',
    license                     varchar(255) NOT NULL default '',
    mirror                      varchar(255) NOT NULL default '',
    price                       varchar(10) NOT NULL default 'Free',
    paypalemail                 varchar(255) NOT NULL default '',
    features                    text NOT NULL,
    requirements                text NOT NULL,
    homepagetitle               varchar(255) NOT NULL default '',
    forumid                     int(11) NOT NULL default '0',
    limitations                 varchar(255) NOT NULL default '30 day trial',
    versiontypes                varchar(255) NOT NULL default 'None',
    dhistory                    text NOT NULL,
    published                   int(11) NOT NULL default '1089662528',
    expired                     int(10) NOT NULL default '0',
    updated                     int(11) NOT NULL default '0',
    offline                     tinyint(1) NOT NULL default '0',
    summary                     text NOT NULL,
    description                 text NOT NULL,
    ipaddress                   varchar(120) NOT NULL default '0',
    notifypub                   int(1) NOT NULL default '0',
    formulize_idreq             int(5) NOT NULL default '0',
    screenshots                 text NOT NULL,
    dohtml                      tinyint(1) NOT NULL default '0',
    dosmiley                    tinyint(1) NOT NULL default '1',
    doxcode                     tinyint(1) NOT NULL default '1',
    doimage                     tinyint(1) NOT NULL default '1',
    dobr                        tinyint(1) NOT NULL default '1',
    PRIMARY KEY (lid),
    KEY cid (cid),
    KEY status (status),
    KEY title (title(40))
) ENGINE=MyISAM;

#
# Dumping data for table `wfdownloads_downloads`
#

# --------------------------------------------------------

#
# Table structure for table `wfdownloads_indexpage`
#

CREATE TABLE wfdownloads_indexpage (
    indeximage                  varchar(255) NOT NULL default 'blank.png',
    indexheading                varchar(255) NOT NULL default 'Wfdownloads',
    indexheader                 text NOT NULL,
    indexfooter                 text NOT NULL,
    nohtml                      tinyint(8) NOT NULL default '1',
    nosmiley                    tinyint(8) NOT NULL default '1',
    noxcodes                    tinyint(8) NOT NULL default '1',
    noimages                    tinyint(8) NOT NULL default '1',
    nobreak                     tinyint(4) NOT NULL default '1',
    indexheaderalign            varchar(25) NOT NULL default 'left',
    indexfooteralign            varchar(25) NOT NULL default 'center',
    FULLTEXT KEY indexheading (indexheading),
    FULLTEXT KEY indexheader (indexheader),
    FULLTEXT KEY indexfooter (indexfooter)
) ENGINE=MyISAM;

#
# Dumping data for table `wfdownloads_indexpage`
#

INSERT INTO wfdownloads_indexpage VALUES ('logo-en.gif', 'Wfdownloads', '<div><b>Welcome to the WF Download Section.</b></div>', 'Wfdownloads', 1, 1, 1, 1, 1, 'left', 'center');

# --------------------------------------------------------

#
# Table structure for table `wfdownloads_mimetypes`
#

CREATE TABLE wfdownloads_mimetypes (
    mime_id                     int(11) NOT NULL auto_increment,
    mime_ext                    varchar(60) NOT NULL default '',
    mime_types                  text NOT NULL,
    mime_name                   varchar(255) NOT NULL default '',
    mime_admin                  int(1) NOT NULL default '1',
    mime_user                   int(1) NOT NULL default '0',
    KEY mime_id (mime_id)
) ENGINE=MyISAM;

#
# Dumping data for table `wfdownloads_mimetypes`
#

INSERT INTO wfdownloads_mimetypes VALUES (1, 'bin', 'application/octet-stream', 'Binary File/Linux Executable', 0, 0);
INSERT INTO wfdownloads_mimetypes VALUES (2, 'dms', 'application/octet-stream', 'Amiga DISKMASHER Compressed Archive', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (3, 'class', 'application/octet-stream', 'Java Bytecode', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (4, 'so', 'application/octet-stream', 'UNIX Shared Library Function', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (5, 'dll', 'application/octet-stream', 'Dynamic Link Library', 0, 0);
INSERT INTO wfdownloads_mimetypes VALUES (6, 'hqx', 'application/binhex application/mac-binhex application/mac-binhex40', 'Macintosh BinHex 4 Compressed Archive', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (7, 'cpt', 'application/mac-compactpro application/compact_pro', 'Compact Pro Archive', 0, 0);
INSERT INTO wfdownloads_mimetypes VALUES (8, 'lha', 'application/lha application/x-lha application/octet-stream application/x-compress application/x-compressed application/maclha', 'Compressed Archive File', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (9, 'lzh', 'application/lzh application/x-lzh application/x-lha application/x-compress application/x-compressed application/x-lzh-archive zz-application/zz-winassoc-lzh application/maclha application/octet-stream', 'Compressed Archive File', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (10, 'sh', 'application/x-shar', 'UNIX shar Archive File', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (11, 'shar', 'application/x-shar', 'UNIX shar Archive File', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (12, 'tar', 'application/tar application/x-tar applicaton/x-gtar multipart/x-tar application/x-compress application/x-compressed', 'Tape Archive File', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (13, 'gtar', 'application/x-gtar', 'GNU tar Compressed File Archive', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (14, 'ustar', 'application/x-ustar multipart/x-ustar', 'POSIX tar Compressed Archive', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (15, 'zip', 'application/zip application/x-zip application/x-zip-compressed application/octet-stream application/x-compress application/x-compressed multipart/x-zip', 'Compressed Archive File', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (16, 'exe', 'application/exe application/x-exe application/dos-exe application/x-winexe application/msdos-windows application/x-msdos-program', 'Executable File', 0, 0);
INSERT INTO wfdownloads_mimetypes VALUES (17, 'wmz', 'application/x-ms-wmz', 'Windows Media Compressed Skin File', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (18, 'wmd', 'application/x-ms-wmd', 'Windows Media Download File', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (19, 'doc', 'application/msword application/doc appl/text application/vnd.msword application/vnd.ms-word application/winword application/word application/x-msw6 application/x-msword', 'Word Document', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (20, 'pdf', 'application/pdf application/acrobat application/x-pdf applications/vnd.pdf text/pdf', 'Acrobat Portable Document Format', 1, 1);
INSERT INTO wfdownloads_mimetypes VALUES (21, 'eps', 'application/eps application/postscript application/x-eps image/eps image/x-eps', 'Encapsulated PostScript', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (22, 'ps', 'application/postscript application/ps application/x-postscript application/x-ps text/postscript', 'PostScript', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (23, 'smi', 'application/smil', 'SMIL Multimedia', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (24, 'smil', 'application/smil', 'Synchronized Multimedia Integration Language', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (25, 'wmlc', 'application/vnd.wap.wmlc ', 'Compiled WML Document', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (26, 'wmlsc', 'application/vnd.wap.wmlscriptc', 'Compiled WML Script', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (27, 'vcd', 'application/x-cdlink', 'Virtual CD-ROM CD Image File', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (28, 'pgn', 'application/formstore', 'Picatinny Arsenal Electronic Formstore Form in TIFF Format', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (29, 'cpio', 'application/x-cpio', 'UNIX CPIO Archive', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (30, 'csh', 'application/x-csh', 'Csh Script', 0, 0);
INSERT INTO wfdownloads_mimetypes VALUES (31, 'dcr', 'application/x-director', 'Shockwave Movie', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (32, 'dir', 'application/x-director', 'Macromedia Director Movie', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (33, 'dxr', 'application/x-director application/vnd.dxr', 'Macromedia Director Protected Movie File', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (34, 'dvi', 'application/x-dvi', 'TeX Device Independent Document', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (35, 'spl', 'application/x-futuresplash', 'Macromedia FutureSplash File', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (36, 'hdf', 'application/x-hdf', 'Hierarchical Data Format File', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (37, 'js', 'application/x-javascript text/javascript', 'JavaScript Source Code', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (38, 'skp', 'application/x-koan application/vnd-koan koan/x-skm application/vnd.koan', 'SSEYO Koan Play File', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (39, 'skd', 'application/x-koan application/vnd-koan koan/x-skm application/vnd.koan', 'SSEYO Koan Design File', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (40, 'skt', 'application/x-koan application/vnd-koan koan/x-skm application/vnd.koan', 'SSEYO Koan Template File', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (41, 'skm', 'application/x-koan application/vnd-koan koan/x-skm application/vnd.koan', 'SSEYO Koan Mix File', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (42, 'latex', 'application/x-latex text/x-latex', 'LaTeX Source Document', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (43, 'nc', 'application/x-netcdf text/x-cdf', 'Unidata netCDF Graphics', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (44, 'cdf', 'application/cdf application/x-cdf application/netcdf application/x-netcdf text/cdf text/x-cdf', 'Channel Definition Format', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (45, 'swf', 'application/x-shockwave-flash application/x-shockwave-flash2-preview application/futuresplash image/vnd.rn-realflash', 'Macromedia Flash Format File', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (46, 'sit', 'application/stuffit application/x-stuffit application/x-sit', 'StuffIt Compressed Archive File', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (47, 'tcl', 'application/x-tcl', 'TCL/TK Language Script', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (48, 'tex', 'application/x-tex', 'LaTeX Source', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (49, 'texinfo', 'application/x-texinfo', 'TeX', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (50, 'texi', 'application/x-texinfo', 'TeX', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (51, 't', 'application/x-troff', 'TAR Tape Archive Without Compression', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (52, 'tr', 'application/x-troff', 'Unix Tape Archive = TAR without compression (tar)', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (53, 'src', 'application/x-wais-source', 'Sourcecode', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (54, 'xhtml', 'application/xhtml+xml', 'Extensible HyperText Markup Language File', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (55, 'xht', 'application/xhtml+xml', 'Extensible HyperText Markup Language File', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (56, 'au', 'audio/basic audio/x-basic audio/au audio/x-au audio/x-pn-au audio/rmf audio/x-rmf audio/x-ulaw audio/vnd.qcelp audio/x-gsm audio/snd', 'ULaw/AU Audio File', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (57, 'XM', 'audio/xm audio/x-xm audio/module-xm audio/mod audio/x-mod', 'Fast Tracker 2 Extended Module', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (58, 'snd', 'audio/basic', 'Macintosh Sound Resource', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (59, 'mid', 'audio/mid audio/m audio/midi audio/x-midi application/x-midi audio/soundtrack', 'Musical Instrument Digital Interface MIDI-sequention Sound', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (60, 'midi', 'audio/mid audio/m audio/midi audio/x-midi application/x-midi', 'Musical Instrument Digital Interface MIDI-sequention Sound', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (61, 'kar', 'audio/midi audio/x-midi audio/mid x-music/x-midi', 'Karaoke MIDI File', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (62, 'mpga', 'audio/mpeg audio/mp3 audio/mgp audio/m-mpeg audio/x-mp3 audio/x-mpeg audio/x-mpg video/mpeg', 'Mpeg-1 Layer3 Audio Stream', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (63, 'mp2', 'video/mpeg audio/mpeg', 'MPEG Audio Stream, Layer II', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (64, 'mp3', 'audio/mpeg audio/x-mpeg audio/mp3 audio/x-mp3 audio/mpeg3 audio/x-mpeg3 audio/mpg audio/x-mpg audio/x-mpegaudio', 'MPEG Audio Stream, Layer III', 1, 1);
INSERT INTO wfdownloads_mimetypes VALUES (65, 'aif', 'audio/aiff audio/x-aiff sound/aiff audio/rmf audio/x-rmf audio/x-pn-aiff audio/x-gsm audio/x-midi audio/vnd.qcelp', 'Audio Interchange File', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (66, 'aiff', 'audio/aiff audio/x-aiff sound/aiff audio/rmf audio/x-rmf audio/x-pn-aiff audio/x-gsm audio/mid audio/x-midi audio/vnd.qcelp', 'Audio Interchange File', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (67, 'aifc', 'audio/aiff audio/x-aiff audio/x-aifc sound/aiff audio/rmf audio/x-rmf audio/x-pn-aiff audio/x-gsm audio/x-midi audio/mid audio/vnd.qcelp', 'Audio Interchange File', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (68, 'm3u', 'audio/x-mpegurl audio/mpeg-url application/x-winamp-playlist audio/scpls audio/x-scpls', 'MP3 Playlist File', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (69, 'ram', 'audio/x-pn-realaudio audio/vnd.rn-realaudio audio/x-pm-realaudio-plugin audio/x-pn-realvideo audio/x-realaudio video/x-pn-realvideo text/plain', 'RealMedia Metafile', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (70, 'rm', 'application/vnd.rn-realmedia audio/vnd.rn-realaudio audio/x-pn-realaudio audio/x-realaudio audio/x-pm-realaudio-plugin', 'RealMedia Streaming Media', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (71, 'rpm', 'audio/x-pn-realaudio audio/x-pn-realaudio-plugin audio/x-pnrealaudio-plugin video/x-pn-realvideo-plugin audio/x-mpegurl application/octet-stream', 'RealMedia Player Plug-in', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (72, 'ra', 'audio/vnd.rn-realaudio audio/x-pn-realaudio audio/x-realaudio audio/x-pm-realaudio-plugin video/x-pn-realvideo', 'RealMedia Streaming Media', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (73, 'wav', 'audio/wav audio/x-wav audio/wave audio/x-pn-wav', 'Waveform Audio', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (74, 'wax', ' audio/x-ms-wax', 'Windows Media Audio Redirector', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (75, 'wma', 'audio/x-ms-wma video/x-ms-asf', 'Windows Media Audio File', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (76, 'bmp', 'image/bmp image/x-bmp image/x-bitmap image/x-xbitmap image/x-win-bitmap image/x-windows-bmp image/ms-bmp image/x-ms-bmp application/bmp application/x-bmp application/x-win-bitmap application/preview', 'Windows OS/2 Bitmap Graphics', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (77, 'gif', 'image/gif image/x-xbitmap image/gi_', 'Graphic Interchange Format', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (78, 'ief', 'image/ief', 'Image File - Bitmap graphics', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (79, 'jpeg', 'image/jpeg image/jpg image/jpe_ image/pjpeg image/vnd.swiftview-jpeg', 'JPEG/JIFF Image', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (80, 'jpg', 'image/jpeg image/jpg image/jp_ application/jpg application/x-jpg image/pjpeg image/pipeg image/vnd.swiftview-jpeg image/x-xbitmap', 'JPEG/JIFF Image', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (81, 'jpe', 'image/jpeg', 'JPEG/JIFF Image', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (82, 'png', 'image/png application/png application/x-png', 'Portable (Public) Network Graphic', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (83, 'tiff', 'image/tiff', 'Tagged Image Format File', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (84, 'tif', 'image/tif image/x-tif image/tiff image/x-tiff application/tif application/x-tif application/tiff application/x-tiff', 'Tagged Image Format File', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (85, 'ico', 'image/ico image/x-icon application/ico application/x-ico application/x-win-bitmap image/x-win-bitmap application/octet-stream', 'Windows Icon', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (86, 'wbmp', 'image/vnd.wap.wbmp', 'Wireless Bitmap File Format', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (87, 'ras', 'application/ras application/x-ras image/ras', 'Sun Raster Graphic', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (88, 'pnm', 'image/x-portable-anymap', 'PBM Portable Any Map Graphic Bitmap', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (89, 'pbm', 'image/portable bitmap image/x-portable-bitmap image/pbm image/x-pbm', 'UNIX Portable Bitmap Graphic', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (90, 'pgm', 'image/x-portable-graymap image/x-pgm', 'Portable Graymap Graphic', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (91, 'ppm', 'image/x-portable-pixmap application/ppm application/x-ppm image/x-p image/x-ppm', 'PBM Portable Pixelmap Graphic', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (92, 'rgb', 'image/rgb image/x-rgb', 'Silicon Graphics RGB Bitmap', 1, 1);
INSERT INTO wfdownloads_mimetypes VALUES (93, 'xbm', 'image/x-xpixmap image/x-xbitmap image/xpm image/x-xpm', 'X Bitmap Graphic', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (94, 'xpm', 'image/x-xpixmap', 'BMC Software Patrol UNIX Icon File', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (95, 'xwd', 'image/x-xwindowdump image/xwd image/x-xwd application/xwd application/x-xwd', 'X Windows Dump', 1, 1);
INSERT INTO wfdownloads_mimetypes VALUES (96, 'igs', 'model/iges application/iges application/x-iges application/igs application/x-igs drawing/x-igs image/x-igs', 'Initial Graphics Exchange Specification Format', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (97, 'css', 'application/css-stylesheet text/css', 'Hypertext Cascading Style Sheet', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (98, 'html', 'text/html text/plain', 'Hypertext Markup Language', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (99, 'htm', 'text/html', 'Hypertext Markup Language', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (100, 'txt', 'text/plain application/txt browser/internal', 'Text File', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (101, 'rtf', 'application/rtf application/x-rtf text/rtf text/richtext application/msword application/doc application/x-soffice', 'Rich Text Format File', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (102, 'wml', 'text/vnd.wap.wml text/wml', 'Website META Language File', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (103, 'wmls', 'text/vnd.wap.wmlscript', 'WML Script', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (104, 'etx', 'text/x-setext', 'SetText Structure Enhanced Text', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (105, 'xml', 'text/xml application/xml application/x-xml', 'Extensible Markup Language File', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (106, 'xsl', 'text/xml', 'XML Stylesheet', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (107, 'php', 'application/x-httpd-php text/php application/php magnus-internal/shellcgi application/x-php', 'PHP Script', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (108, 'php3', 'text/php3 application/x-httpd-php', 'PHP Script', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (109, 'mpeg', 'video/mpeg', 'MPEG Movie', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (110, 'mpg', 'video/mpeg video/mpg video/x-mpg video/mpeg2 application/x-pn-mpg video/x-mpeg video/x-mpeg2a audio/mpeg audio/x-mpeg image/mpg', 'MPEG 1 System Stream', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (111, 'mpe', 'video/mpeg', 'MPEG Movie Clip', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (112, 'qt', 'video/quicktime audio/aiff audio/x-wav video/flc', 'QuickTime Movie', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (113, 'mov', 'video/quicktime video/x-quicktime image/mov audio/aiff audio/x-midi audio/x-wav video/avi', 'QuickTime Video Clip', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (114, 'avi', 'video/avi video/msvideo video/x-msvideo image/avi video/xmpg2 application/x-troff-msvideo audio/aiff audio/avi', 'Audio Video Interleave File', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (115, 'movie', 'video/sgi-movie video/x-sgi-movie', 'QuickTime Movie', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (116, 'asf', 'audio/asf application/asx video/x-ms-asf-plugin application/x-mplayer2 video/x-ms-asf application/vnd.ms-asf video/x-ms-asf-plugin video/x-ms-wm video/x-ms-wmx', 'Advanced Streaming Format', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (117, 'asx', 'video/asx application/asx video/x-ms-asf-plugin application/x-mplayer2 video/x-ms-asf application/vnd.ms-asf video/x-ms-asf-plugin video/x-ms-wm video/x-ms-wmx video/x-la-asf', 'Advanced Stream Redirector File', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (118, 'wmv', 'video/x-ms-wmv', 'Windows Media File', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (119, 'wvx', 'video/x-ms-wvx', 'Windows Media Redirector', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (120, 'wm', 'video/x-ms-wm', 'Windows Media A/V File', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (121, 'wmx', 'video/x-ms-wmx', 'Windows Media Player A/V Shortcut', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (122, 'ice', 'x-conference-xcooltalk', 'Cooltalk Audio', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (123, 'rar', 'application/octet-stream', 'WinRAR Compressed Archive', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (124, 'mp4', 'video/mp4', 'MPEG-4', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (125, 'flv', 'video/x-flv', 'Flash Video', 1, 0);
INSERT INTO wfdownloads_mimetypes VALUES (126, 'm3u8', 'application/x-mpegURL', 'iPhone Index', 0, 0);
INSERT INTO wfdownloads_mimetypes VALUES (127, 'ts', 'video/MP2T', 'iPhone Segment', 0, 0);
INSERT INTO wfdownloads_mimetypes VALUES (128, '3gp', 'video/3gpp', '3GP Mobile', 0, 0);

# --------------------------------------------------------

#
# Table structure for table `wfdownloads_mod`
#

CREATE TABLE wfdownloads_mod (
    requestid                   int(11) NOT NULL auto_increment,
    lid                         int(11) unsigned NOT NULL default '0',
    cid                         int(5) unsigned NOT NULL default '0',
    title                       varchar(255) NOT NULL default '',
    url                         varchar(255) NOT NULL default '',
    filename                    varchar(150) NOT NULL default '',
    filetype                    varchar(100) NOT NULL default '',
    homepage                    varchar(255) NOT NULL default '',
    version                     varchar(20) NOT NULL default '',
    size                        int(8) NOT NULL default '0',
    platform                    varchar(50) NOT NULL default '',
    screenshot                  varchar(255) NOT NULL default '',
    screenshot2                 varchar(255) NOT NULL default '',
    screenshot3                 varchar(255) NOT NULL default '',
    screenshot4                 varchar(255) NOT NULL default '',
    submitter                   int(11) NOT NULL default '0',
    publisher                   text NOT NULL,
    status                      tinyint(2) NOT NULL default '0',
    date                        int(10) NOT NULL default '0',
    hits                        int(11) unsigned NOT NULL default '0',
    rating                      double(6,4) NOT NULL default '0.0000',
    votes                       int(11) unsigned NOT NULL default '0',
    comments                    int(11) unsigned NOT NULL default '0',
    license                     varchar(255) NOT NULL default '',
    mirror                      varchar(255) NOT NULL default '',
    price                       varchar(10) NOT NULL default 'Free',
    paypalemail                 varchar(255) NOT NULL default '',
    features                    text NOT NULL,
    requirements                text NOT NULL,
    homepagetitle               varchar(255) NOT NULL default '',
    forumid                     int(11) NOT NULL default '0',
    limitations                 varchar(255) NOT NULL default '30 day trial',
    versiontypes                varchar(255) NOT NULL default 'None',
    dhistory                    text NOT NULL,
    published                   int(10) NOT NULL default '0',
    expired                     int(10) NOT NULL default '0',
    updated                     int(11) NOT NULL default '0',
    offline                     tinyint(1) NOT NULL default '0',
    summary                     text NOT NULL,
    description                 text NOT NULL,
    modifysubmitter             int(11) NOT NULL default '0',
    requestdate                 int(11) NOT NULL default '0',
    screenshots                 text NOT NULL,
    dohtml                      tinyint(1) NOT NULL default '0',
    dosmiley                    tinyint(1) NOT NULL default '1',
    doxcode                     tinyint(1) NOT NULL default '1',
    doimage                     tinyint(1) NOT NULL default '1',
    dobr                        tinyint(1) NOT NULL default '1',
    PRIMARY KEY (requestid)
) ENGINE=MyISAM;

#
# Dumping data for table `wfdownloads_mod`
#

# --------------------------------------------------------

#
# Table structure for table `wfdownloads_reviews`
#

CREATE TABLE wfdownloads_reviews (
    review_id                   int(11) unsigned NOT NULL auto_increment,
    lid                         int(11) NOT NULL default '0',
    title                       varchar(255) default NULL,
    review                      text,
    submit                      int(11) NOT NULL default '0',
    date                        int(11) NOT NULL default '0',
    uid                         int(10) NOT NULL default '0',
    rated                       int(11) NOT NULL default '0',
    PRIMARY KEY (review_id),
    KEY categoryid (lid)
) ENGINE=MyISAM;

#
# Dumping data for table `wfdownloads_reviews`
#

# --------------------------------------------------------

#
# Table structure for table `wfdownloads_votedata`
#

CREATE TABLE wfdownloads_votedata (
    ratingid int(11) unsigned NOT NULL auto_increment,
    lid int(11) unsigned NOT NULL default '0',
    ratinguser int(11) NOT NULL default '0',
    rating tinyint(3) unsigned NOT NULL default '0',
    ratinghostname varchar(60) NOT NULL default '',
    ratingtimestamp int(10) NOT NULL default '0',
    PRIMARY KEY  (ratingid),
    KEY ratinguser (ratinguser),
    KEY ratinghostname (ratinghostname),
    KEY lid (lid)
) ENGINE=MyISAM;

#
# Dumping data for table `wfdownloads_votedata`
#


# --------------------------------------------------------


CREATE TABLE `wfdownloads_meta` (
    `metakey` varchar(50) NOT NULL default '',
    `metavalue` varchar(255) NOT NULL default '',
    PRIMARY KEY (`metakey`)
) ENGINE=MyISAM COMMENT='Wfdownloads by The SmartFactory <www.smartfactory.ca>' ;

#
# Dumping data for table `wfdownloads_meta`
#

INSERT INTO `wfdownloads_meta` VALUES ('version','3.23');


# --------------------------------------------------------

#
# Table structure for table `wfdownloads_mirrors`
#

CREATE TABLE wfdownloads_mirrors (
    mirror_id int(11) unsigned NOT NULL auto_increment,
    lid int(11) NOT NULL default '0',
    title varchar(255) NOT NULL default '',
    homeurl varchar(100) NOT NULL default '',
    location varchar(255) NOT NULL default '',
    continent varchar(255) NOT NULL default '',
    downurl varchar(255) NOT NULL default '',
    submit int(11) NOT NULL default '0',
    date int(11) NOT NULL default '0',
    uid int(10) NOT NULL default '0',
    PRIMARY KEY  (mirror_id),
    KEY categoryid (lid)
) ENGINE=MyISAM;

#
# Dumping data for table `wfdownloads_mirrors`
#

#
# Table structure for table `wfdownloads_ip_log`
#

CREATE TABLE wfdownloads_ip_log (
    ip_logid int(11) NOT NULL auto_increment,
    lid int(11) NOT NULL default '0',
    uid int(11) NOT NULL default '0',
    date int(11) NOT NULL default '0',
    ip_address varchar(20) NOT NULL default '',
    PRIMARY KEY  (ip_logid)
 ) ENGINE=MyISAM;
