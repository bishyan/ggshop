# 创建角色表
create table if not exists ggshop06_role (
    role_id tinyint unsigned not null auto_increment primary key,
    role_name varchar(32) not null comment '角色名称',
    act_list varchar(512) not null default '' comment '权限ids',
    role_desc varchar(256) not null default '' comment '角色描述'
) engine=Myisam charset=utf8 comment '角色表';

# 创建系统菜单表
create table if not exists ggshop06_menu (
    menu_id smallint unsigned not null auto_increment primary key,
    menu_name varchar(30) not null comment '菜单名称',
    pid smallint unsigned not null default 0 comment '父id',
    ctl varchar(30) not null default '' comment '控制器',
    act varchar(30) not null default '' comment '方法',
    url varchar(50) not null default '' comment '控制器@方法, 如goods@index',
    status tinyint unsigned default 1 comment '菜单的状态, 0不显示, 1显示',
    is_use tinyint unsigned default 1 comment '菜单是否启用, 0关闭, 1开启',
    sort_order tinyint unsigned default 20 comment '排序的权重, 0-255, 数字越小越靠前'
) engine=Myisam charset=utf8 comment '系统菜单表';

# 创建管理员表
create table if not exists ggshop06_admin (
    admin_id tinyint unsigned not null auto_increment primary key,
    admin_name varchar(30) not null comment '管理员名称',
    password char(32) not null comment '密码',
    role_id tinyint unsigned not null default 0 comment '所属角色id',
    email varchar(120) not null default '' comment 'email',
    is_use tinyint unsigned not null default 1 comment '是否启用, 0禁用, 1启用',
    create_time int unsigned not null default 0 comment '创建时间'
) engine=Myisam charset=utf8 comment '管理员表';

insert into ggshop06_admin values (1, 'admin', md5('1234abcd'), 1, 'admin@163.com', 1, 0), 
(2, 'bsy', md5('1234abcd'), 2, 'bsy@163.com', 1, 0),(3, 'guoguo', md5('1234abcd'), 3, 'guoguo@163.com', 1, 0);