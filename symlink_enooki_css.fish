for d in app pro cli admin manop webapp
    rm -f $d/assets/css/enooki.css
    ln -s ../../../shared/assets/css/enooki.css $d/assets/css/enooki.css
end