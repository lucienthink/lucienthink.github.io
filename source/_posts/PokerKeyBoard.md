title: Poker 折腾记
date: 2016-09-07 11:40
cover_index: /img/PokerKeyBoard-cover_detail.png
cover_detail: /img/PokerKeyBoard-cover_detail.png
tags: Poker KeyBoard keymap
comments: true
---

对机械键盘有了解的可能都知道这款神器 [Poker](http://www.ikbc.com.cn/).

---

* ikbc 键帽
* charry 轴
* 60% 键盘
* 独创的硬件级键位编程
* 售价399

---
所谓乞丐版 HHKB，也就像我这种屌丝买不起 HHKB 才会选择这个。

进入正题，从某宝入手了一个 Poker2，没买 Poker3 的原因是听说 Poker3 的做工不如 Poker2，虽然 Poker3 有 4 层键位编程，而 Poker2 只有 1 层，但是对于平时使用来讲，1 层编程已经够我们使用的了。

下面 po 一下我的 Poker2

![](/img/PokerKeyBoard-1.png)

![](/img/PokerKeyBoard-2.png)


## Fn 拓展键位

买 Poker 看中的主要是他 60% 的小巧灵活，因为我们在平常的使用中，一般也都只用左边主键盘部分。
但是由于 61 键的 Poker 缺少了上下左右等功能键，所以加入了硬件键位编程，配合 Fn 键和 Pn 键，实现超越 104 键盘的更多键位。

比如：

```
Fn + W = ↑
Fn + A = ←
Fn + D = →
Fn + S = ↓
Fn + 1 = F1
...
Fn + Backspace = Del
Fn + Esc = `
```

## Poker2 硬件编程

Poker2 的硬件编程过程如下：

1. 按 `Fn + 右Ctrl`，进入录制编程模式（空格右灯闪烁）
2. 输入想要对其编程的键（空格右灯变长亮），只能输入一个非 Fn 和 Pn 的键
3. 输入想要编程的内容（空格右灯灾变闪烁），可以输入多个键，使用时会一次响应这多个键
4. 重复 2 和 3 即可继续编程其他键
5. 按 `Fn + 右Ctrl`，结束录制编程模式

使用的时候，按住 `Pn + [步骤2录制的编程键]` 就可以实现在步骤 3 中录制的一串按键。
如果按 `Fn + 右Shift` 会进入编程模式，无须再按 Pn 就可以直接使用步骤 2 中编程编程键了

比如我这边就录制了
```
A = Cmd + Alt + ←
D = Cmd + Alt + →
```
这样以前使用 `Cmd + Alt + ←` 来实现切换上一个 Tab 的功能，现在可以用 Pn + A 来完成


## 实现左手 Fn 键

由于 Poker 的 Fn 键在右手 Alt 键右侧，所以有时候右手使用鼠标或者触摸板的时候，左手单手是无法按“上下左右”等一些拓展键的。

幸好 Poker2 在键盘底面，有 4 个指拨开关，

```
开关1： 左 Win 键和 Cap 键互换
开关2： 右 Ctrl = `
开关3： 左 Win = Fn
开关4： 锁定编程，不允许清空编程键
```

因此在 Mac 下，我们就可以利用 基本用不上的 Cap 键来实现 Fn 的功能，同时又不影响正常的 Alt 和 Cmd。
具体配置方式是：

---
1. 打开`系统偏好设置` -> `键盘` -> `修饰键`；
2. 修改 `Caps Lock键` 为 `Option`，修改 `Option键` 为 `Command`，修改 `Command键` 为 `Option`；
3. 拨动键盘底部的开关 1 和开关 3 ，置为 ON。
---

![](/img/PokerKeyBoard-3.png)
如上图所示

这套改建的原理是：

---
1. 互换 Cmd 和 Alt，将 Cmd 改成习惯的位置
2. 开关 1，将 左 Win 与 Cap 互换，于是， Cap 键现在是 Win 键，开关 3， Win = Fn，所以 Cap 现在是 Fn
3. 由于系统级别改了 Cmd(Win) 键为 Option 键，所以现在的 Win 键位置就不管硬件的编程，始终为 Option(Alt) 键
4. 由于系统级别改了 Option(Alt)  键为 Cmd 键，所以现在的 Option(Alt) 键位置就不管硬件的编程，始终为 Cmd 键
---

这样设置之后，空格左边的按键为 Cmd，左Ctrl右边的键为 Alt，Cap 键为 Fn，于是，左手就可以单手按下“上下左右”了，比普通的104键盘和87键盘都方便许多。


## 使用软件拓展更多改键

使用[Karabiner](https://pqrs.org/osx/karabiner/index.html.en)，可以实现更多键位的修改，具体可以参考其官方文档。

但是当我在公司习惯我的 Poker 键位以后，回到家用之前的 Filco 87 键盘就非常不适应不能使用 `Cap + [wasd]` 实现上下左右等一些操作，于是看用上了 Karabiner 来进行改键。

软件实现改键最重要的一点就是去除 Cap 键的功能，使用 [Seil](https://pqrs.org/osx/karabiner/seil.html) 先将 Cap 键改为一个用不上的键，比如编号为 80 的 F19 键
![](/img/PokerKeyBoard-4.png)

注意这里官方说需要将 Cap 键在系统设置置为 “no action（无操作）”
![](/img/PokerKeyBoard-5.png)

Cap 键准备完成，现在就需要去修改 Karabiner 的配置文件了，
![](/img/PokerKeyBoard-6.png)

去这里找到 Karabiner 的自定义配置文件，贴出我的修改方案：

```
<?xml version="1.0"?>
<root>
    <item>
        <name>F19 to F19</name>
        <appendix>(F19 to Hyper (ctrl+shift+cmd+opt) + F19 Only, send escape)</appendix>
        <identifier>private.f192f19_escape</identifier>
        <autogen>
            --KeyOverlaidModifier--
            KeyCode::F19,
            KeyCode::COMMAND_L,
            ModifierFlag::OPTION_L | ModifierFlag::SHIFT_L | ModifierFlag::CONTROL_L,
            KeyCode::ESCAPE
        </autogen>
    </item>
    <item>
        <name>Hyper S to Down</name>
        <appendix>(Hyper-S to Down arrow)</appendix>
        <identifier>private.hyper-k-down</identifier>
        <autogen>
            --KeyToKey--
            KeyCode::S,
            ModifierFlag::COMMAND_L | ModifierFlag::OPTION_L | ModifierFlag::SHIFT_L | ModifierFlag::CONTROL_L,
            KeyCode::CURSOR_DOWN
        </autogen>
    </item>
    <item>
        <name>Hyper W to Up</name>
        <appendix>(Hyper-W to Up arrow)</appendix>
        <identifier>private.hyper-i-up</identifier>
        <autogen>
            --KeyToKey--
            KeyCode::W,
            ModifierFlag::COMMAND_L | ModifierFlag::OPTION_L | ModifierFlag::SHIFT_L | ModifierFlag::CONTROL_L,
            KeyCode::CURSOR_UP
        </autogen>
    </item>
    <item>
        <name>Hyper A to Left</name>
        <appendix>(Hyper-A to Left arrow)</appendix>
        <identifier>private.hyper-j-left</identifier>
        <autogen>
            --KeyToKey--
            KeyCode::A,
            ModifierFlag::COMMAND_L | ModifierFlag::OPTION_L | ModifierFlag::SHIFT_L | ModifierFlag::CONTROL_L,
            KeyCode::CURSOR_LEFT
        </autogen>
    </item>
    <item>
        <name>Hyper D to Right</name>
        <appendix>(Hyper-D to Right arrow)</appendix>
        <identifier>private.hyper-l-right</identifier>
        <autogen>
            --KeyToKey--
            KeyCode::D,
            ModifierFlag::COMMAND_L | ModifierFlag::OPTION_L | ModifierFlag::SHIFT_L | ModifierFlag::CONTROL_L,
            KeyCode::CURSOR_RIGHT
        </autogen>
    </item>
    <item>
        <name>Hyper Q to Beginning of Line</name>
        <appendix>(Hyper-Q to Beginning of Line)</appendix>
        <identifier>private.hyper-h-beginning-of-line</identifier>
        <autogen>
            --KeyToKey--
            KeyCode::Q,
            ModifierFlag::COMMAND_L | ModifierFlag::OPTION_L | ModifierFlag::SHIFT_L | ModifierFlag::CONTROL_L,
            KeyCode::CURSOR_LEFT,
            ModifierFlag::COMMAND_L
        </autogen>
    </item>
    <item>
        <name>Hyper E to End of Line</name>
        <appendix>(HyperE to End of Line)</appendix>
        <identifier>private.hyper-;-end-of-line</identifier>
        <autogen>
            --KeyToKey--
            KeyCode::E,
            ModifierFlag::COMMAND_L | ModifierFlag::OPTION_L | ModifierFlag::SHIFT_L | ModifierFlag::CONTROL_L,
            KeyCode::CURSOR_RIGHT,
            ModifierFlag::COMMAND_L
        </autogen>
    </item>
</root>
```

这样就保持普通键盘和 Poker 的体验一致

PS：这里只针对 MAC 用户，Win 用户请自行脑补~~~~~