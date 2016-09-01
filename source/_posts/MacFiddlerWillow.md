title: Mac、Fiddler、Willow做你想做的任何事
date: 2016-07-15 11:11
cover_index: /img/MacFiddlerWillow-cover_index.png
cover_detail: /img/MacFiddlerWillow-cover_detail.jpg
tags: Mac Fiddler Willow
comments: true
---

# 缘由

在 windows 下面有一款非常强大的抓包工具-- Fiddler，配合 AlloyTeam 开发的 Willow，用过的你懂的。

![](/img/MacFiddlerWillow-1.png)

自从换了 mac 来做开发以后，由于 Fiddler 是基于 .net 开发的，没办法直接在 mac 下使用。
网上有很多替代解决方案，比如 [Charles](https://www.charlesproxy.com/)、[Mono Fiddler](http://www.mono-project.com/download/)、[nProxy](https://github.com/goddyZhao/nproxy)。
博主经过各种尝试之后，发现这些东西总是不能满足之前的各种复杂需求，于是自己搭了一个虚拟机，装上了 fiddler，利用 proxifier 将请求转发到虚拟机的 Fiddler。流程图如下：

![](/img/MacFiddlerWillow-process.png)

具体请按照如下说明安装配置软件：

## Parallels Desktop

[Parallels Desktop](http://www.parallels.com/cn/) 是一款Mac下的虚拟机软件，不同于 VM 等虚拟机的是，它独有的“硬件虚拟化”，能够100%发挥 Mac 的硬件能力，用它打 LOL 都不成问题。

用 Parallels Desktop 安装一个 win7，分配 2G 内存给它，基本就够我使用了（由于其优秀的内存管理，2G内存的 Win7 也并不会卡）。
这里注意，为了防止不卡，要把 cpu 开到最大（ cpu 会按需使用，不用担心给的过大），显存调到 1G 以上。

![](/img/MacFiddlerWillow-2.png)

![](/img/MacFiddlerWillow-3.png)

## Proxifier

[Proxifier](https://www.proxifier.com/) 是一款很强大的代理软件，支持https、socket等多种代理模式，而且还支持指定软件、指定ip制定代理规则。

打开软件后，点击 Proxies，进入如下 Proxies 的列表，点击 add 添加代理。

![](/img/MacFiddlerWillow-4.png)

然后填入代理的 ip 和端口，这里 ip 就是虚拟机的 ip，端口是 Fiddler 默认的 8888 端口，Protocol 选 HTTPS。

![](/img/MacFiddlerWillow-5.png)

然后点击 Rules，进入规则列表，点击 add 添加规则。

![](/img/MacFiddlerWillow-6.png)

点击 Applications 右下的加号，选择你所要代理的软件，这里我们选 chrome 就好了，Target Hosts 处填入你需要匹配的 ip，如果全部都走代理，这里就不填，Action 中选择刚才在 Proxies 中配置的代理。

![](/img/MacFiddlerWillow-7.png)

点击 ok 后规则就生效了。

## Fiddler + Willow

下载安装 [Fiddler](https://www.telerik.com/download/fiddler)，然后 下载安装 [Willow](/data/willow.7z)（Fiddler4 版本的）。

Fiddler4 和 Fiddler2 的区别就在于分别用的 .net4 和 .net2 来开发的。
注意安装 Willow 的时候需要管理员权限执行。

安装完成后，打开 Fiddler，会发现在右边选项栏中出现一个灰色的 Willow 图标，点击 Willow，

![](/img/MacFiddlerWillow-8.png)

然后右击下方空白处，下拉中选择 Add Project，然后输入一个名称，一个项目就建好了。

![](/img/MacFiddlerWillow-9.png)

然后右击你建立的项目，下拉中 Add Rules（添加替换本地文件规则）、Add Host（添加host）、Add Exten（添加拓展）。

![](/img/MacFiddlerWillow-10.png)

这里详细讲一下这3个规则有什么不同。

Rules 里面，可以在 Match 中写正则，匹配你想要替换的路径，Action 中写你本地的文件，也可以构造各种状态码的请求。

![](/img/MacFiddlerWillow-11.png)

Host 很简单，就是和平时配系统 host 一样，填 ip 和 domain 就好了。这里配置 Host 好的一点是即时生效，而系统 host 有时可能需要等待一段时间才会生效。

![](/img/MacFiddlerWillow-12.png)

Exten 提供了十分强大的功能，Match 里面填写你要匹配的路径（支持正则和模糊匹配），Action 中，第一个输入框填的是“发送请求延迟”（Request Delay），第二个是“收到响应延迟”（Response Delay），第三个是 Host，如图我们就填入一段链接（可以只填一部分，模糊匹配），匹配到我们想要的 Host，匹配到的 Exten，优先级要高于之前的 Host 配置。
Header 中还能给 Request 和 Response 中添加内容。

![](/img/MacFiddlerWillow-13.png)

这里再强调一下优先级，Rules > Exten > Host，匹配到 Rule 左边抓取的包会显示“橙色”，Host会显示“紫色”，Exten会显示“蓝色”，方便大家寻找。

当然 Fiddler 本身还提供给很多其他的强大的功能，比如抓包改包、模拟请求等等，感兴趣的可以自行谷歌。


# 结尾

这种方案在我平时的开发中是非常适用的，当然，如果你电脑内存小，或者不喜欢装 Win，还是有很多其他的方案可以替代的，像我有一个同事就是在本地装了个 Nginx，通过 Nginx 来做代理转发，再配合 Charles 来做替换文件，也可以达到目的。如果你还有什么更好的办法，不妨留言告诉我。