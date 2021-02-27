create table ACTOR
(
	id_actor   int          not null,
	name_actor varchar(100) null,
	primary key (id_actor)
);

create table BADGE
(
	id_badge          int auto_increment
		primary key,
	name_badge        varchar(15)  not null,
	image_badge       varchar(255) null,
	description_badge varchar(255) null
);

create table CATEGORY
(
	id_category   int auto_increment
		primary key,
	name_category varchar(15) not null
);

create table MEMBER
(
	email            varchar(100) not null,
	pseudo           varchar(20)  not null,
	password         char(60)     not null,
	photo            varchar(255) null,
	gender           char(5)      null,
	birth_date       date         null,
	city             varchar(60)  null,
	country          varchar(50)  null,
	account_status   varchar(15)  not null,
	account_role     varchar(15)  not null,
	token            varchar(30)  null,
	date_inscription date         not null,
	verified_email   varchar(30)  null,
	banned_date      date         null,
	banned_time      int          null,
	primary key (email),
	constraint MEMBER_pseudo_uindex
		unique (pseudo)
);

create table BADGED_MEMBER
(
	badge      int          not null,
	member     varchar(100) not null,
	date_badge date         not null,
	primary key (badge, member),
	constraint BADGED_MEMBER_ibfk_1
		foreign key (badge) references BADGE (id_badge),
	constraint BADGED_MEMBER_ibfk_2
		foreign key (member) references MEMBER (email)
);

create index member
	on BADGED_MEMBER (member);

create table BLOCKED_MEMBER
(
	blocked_member  varchar(100) not null,
	blocking_member varchar(100) not null,
	date_block      date         not null,
	primary key (blocked_member, blocking_member),
	constraint BLOCKED_MEMBER_ibfk_1
		foreign key (blocked_member) references MEMBER (email),
	constraint BLOCKED_MEMBER_ibfk_2
		foreign key (blocking_member) references MEMBER (email)
);

create index blocking_member
	on BLOCKED_MEMBER (blocking_member);

create table FOLLOWED_MEMBER
(
	followed_member  varchar(100) not null,
	following_member varchar(100) not null,
	date_follow      date         not null,
	primary key (followed_member, following_member),
	constraint FOLLOWED_MEMBER_ibfk_1
		foreign key (followed_member) references MEMBER (email),
	constraint FOLLOWED_MEMBER_ibfk_2
		foreign key (following_member) references MEMBER (email)
);

create index following_member
	on FOLLOWED_MEMBER (following_member);

create table LIST
(
	id_list          int auto_increment
		primary key,
	name_list        varchar(50)  not null,
	visibility_list  varchar(10)  not null,
	description_list varchar(255) null,
	date_list        date         not null,
	member           varchar(100) not null,
	constraint LIST_ibfk_1
		foreign key (member) references MEMBER (email)
);

create index member
	on LIST (member);

create table MESSAGE
(
	id_message       int auto_increment
		primary key,
	date_message     date         not null,
	text_message     text         not null,
	sending_member   varchar(100) null,
	receiving_member varchar(100) null,
	type             varchar(7)   null,
	constraint MESSAGE_ibfk_1
		foreign key (sending_member) references MEMBER (email),
	constraint MESSAGE_ibfk_2
		foreign key (receiving_member) references MEMBER (email)
);

create index receiving_member
	on MESSAGE (receiving_member);

create index sending_member
	on MESSAGE (sending_member);

create table NETWORK
(
	id_network      int auto_increment
		primary key,
	name_network    varchar(50) null,
	country_network varchar(50) null
);

create table REPORTED_MEMBER
(
	reported_member        varchar(100) not null,
	reporting_member       varchar(100) not null,
	reason_reported_member varchar(15)  not null,
	text_reported_member   text         not null,
	date_reported_member   date         not null,
	primary key (reported_member, reporting_member),
	constraint REPORTED_MEMBER_ibfk_1
		foreign key (reported_member) references MEMBER (email),
	constraint REPORTED_MEMBER_ibfk_2
		foreign key (reporting_member) references MEMBER (email)
);

create index reporting_member
	on REPORTED_MEMBER (reporting_member);

create table SUGGESTION
(
	id_suggestion          int auto_increment
		primary key,
	name_suggestion        varchar(60)  not null,
	first_aired_suggestion date         not null,
	image_suggestion       varchar(255) null,
	text_suggestion        text         null,
	date_suggestion        date         not null,
	suggester              varchar(100) not null,
	constraint SUGGESTION_ibfk_1
		foreign key (suggester) references MEMBER (email)
);

create index suggester
	on SUGGESTION (suggester);

create table TV_SHOW
(
	id_show           int          not null,
	name_show         varchar(60)  not null,
	production_status varchar(10)  null,
	runtime_show      int          null,
	first_aired_show  date         null,
	image_show        varchar(255) null,
	summary_show      text         null,
	last_updated      datetime     null,
	primary key (id_show)
);

create table BROADCAST
(
	tv_show int not null,
	network int not null,
	primary key (tv_show, network),
	constraint BROADCAST_ibfk_1
		foreign key (tv_show) references TV_SHOW (id_show),
	constraint BROADCAST_ibfk_2
		foreign key (network) references NETWORK (id_network)
);

create index network
	on BROADCAST (network);

create table CASTING
(
	tv_show     int          not null,
	actor       int          not null,
	role_actor  varchar(100) null,
	photo_actor varchar(255) null,
	primary key (tv_show, actor),
	constraint CASTING_ibfk_1
		foreign key (tv_show) references TV_SHOW (id_show),
	constraint CASTING_ibfk_2
		foreign key (actor) references ACTOR (id_actor)
);

create index actor
	on CASTING (actor);

create table CATEGORIZED_SHOW
(
	category int not null,
	tv_show  int not null,
	primary key (category, tv_show),
	constraint CATEGORIZED_SHOW_ibfk_1
		foreign key (tv_show) references TV_SHOW (id_show),
	constraint CATEGORIZED_SHOW_ibfk_2
		foreign key (category) references CATEGORY (id_category)
);

create index tv_show
	on CATEGORIZED_SHOW (tv_show);

create table COMMENT
(
	id_comment          int auto_increment
		primary key,
	text_comment        text         not null,
	date_comment        date         not null,
	is_modified_comment char         null,
	member              varchar(100) not null,
	tv_show             int          not null,
	constraint COMMENT_ibfk_1
		foreign key (member) references MEMBER (email),
	constraint COMMENT_ibfk_2
		foreign key (tv_show) references TV_SHOW (id_show)
);

create index member
	on COMMENT (member);

create index tv_show
	on COMMENT (tv_show);

create table FOLLOWED_SHOW
(
	member                     varchar(100) not null,
	tv_show                    int          not null,
	status_followed_show       varchar(10)  null,
	notification_followed_show char         null,
	date_followed_show         date         not null,
	mark_followed_show         int          null,
	primary key (member, tv_show),
	constraint FOLLOWED_SHOW_ibfk_1
		foreign key (member) references MEMBER (email),
	constraint FOLLOWED_SHOW_ibfk_2
		foreign key (tv_show) references TV_SHOW (id_show)
);

create index tv_show
	on FOLLOWED_SHOW (tv_show);

create table IN_LIST
(
	list    int not null,
	tv_show int not null,
	primary key (list, tv_show),
	constraint IN_LIST_ibfk_1
		foreign key (list) references LIST (id_list),
	constraint IN_LIST_ibfk_2
		foreign key (tv_show) references TV_SHOW (id_show)
);

create index tv_show
	on IN_LIST (tv_show);

create table LIKED_COMMENT
(
	member  varchar(100) not null,
	comment int          not null,
	primary key (member, comment),
	constraint LIKED_COMMENT_ibfk_1
		foreign key (member) references MEMBER (email),
	constraint LIKED_COMMENT_ibfk_2
		foreign key (comment) references COMMENT (id_comment)
);

create index comment
	on LIKED_COMMENT (comment);

create table RECOMMENDATION
(
	id_reco          int auto_increment
		primary key,
	text_reco        text         not null,
	date_reco        date         not null,
	recommended_show int          not null,
	hosting_show     int          not null,
	member           varchar(100) not null,
	constraint RECOMMENDATION_ibfk_1
		foreign key (member) references MEMBER (email),
	constraint RECOMMENDATION_ibfk_2
		foreign key (recommended_show) references TV_SHOW (id_show),
	constraint RECOMMENDATION_ibfk_3
		foreign key (hosting_show) references TV_SHOW (id_show)
);

create index hosting_show
	on RECOMMENDATION (hosting_show);

create index member
	on RECOMMENDATION (member);

create index recommended_show
	on RECOMMENDATION (recommended_show);

create table REPLY
(
	id_reply          int auto_increment
		primary key,
	text_reply        text         not null,
	is_modified_reply char         null,
	`date_reply`     date         not null,
	member            varchar(100) not null,
	comment           int          not null,
	constraint REPLY_ibfk_1
		foreign key (member) references MEMBER (email),
	constraint REPLY_ibfk_2
		foreign key (comment) references COMMENT (id_comment)
);

create index comment
	on REPLY (comment);

create index member
	on REPLY (member);

create table REPORTED_COMMENT
(
	member           varchar(100) not null,
	comment          int          not null,
	`reason_report` varchar(15)  not null,
	`text_report`   text         not null,
	date_report      date         not null,
	primary key (member, comment),
	constraint REPORTED_COMMENT_ibfk_1
		foreign key (member) references MEMBER (email),
	constraint REPORTED_COMMENT_ibfk_2
		foreign key (comment) references COMMENT (id_comment)
);

create index comment
	on REPORTED_COMMENT (comment);

create table REPORTED_REPLY
(
	member           varchar(100) not null,
	reply            int          not null,
	`reason_report` varchar(15)  not null,
	`text_report`   text         not null,
	date_report      date         not null,
	primary key (member, reply),
	constraint REPORTED_REPLY_ibfk_1
		foreign key (member) references MEMBER (email),
	constraint REPORTED_REPLY_ibfk_2
		foreign key (reply) references REPLY (id_reply)
);

create index reply
	on REPORTED_REPLY (reply);

create table SEASON
(
	id_season int auto_increment
		primary key,
	nb_season int not null,
	tv_show   int not null,
	constraint SEASON_ibfk_1
		foreign key (tv_show) references TV_SHOW (id_show)
);

create table EPISODE
(
	id_episode          int          not null,
	nb_episode          int          not null,
	name_episode        varchar(50)  null,
	first_aired_episode date         null,
	director_episode    varchar(100) null,
	author_episode      varchar(100) null,
	summary_episode     text         null,
	season              int          not null,
	primary key (id_episode),
	constraint EPISODE_ibfk_1
		foreign key (season) references SEASON (id_season)
);

create index season
	on EPISODE (season);

create index tv_show
	on SEASON (tv_show);

create table VOTED_RECO
(
	member         varchar(100) not null,
	recommendation int          not null,
	primary key (member, recommendation),
	constraint VOTED_RECO_ibfk_1
		foreign key (member) references MEMBER (email),
	constraint VOTED_RECO_ibfk_2
		foreign key (recommendation) references RECOMMENDATION (id_reco)
);

create index recommendation
	on VOTED_RECO (recommendation);

create table WATCHED_EPISODES
(
	member       varchar(100) not null,
	episode      int          not null,
	date_watched date         not null,
	primary key (member, episode),
	constraint WATCHED_EPISODES_ibfk_1
		foreign key (member) references MEMBER (email),
	constraint WATCHED_EPISODES_ibfk_2
		foreign key (episode) references EPISODE (id_episode)
);

create index episode
	on WATCHED_EPISODES (episode);

