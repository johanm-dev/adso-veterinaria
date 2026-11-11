package com.sena.veterinaria;

import android.app.Activity;
import android.annotation.SuppressLint;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Bitmap;
import android.net.Uri;
import android.os.Bundle;
import android.os.Handler;
import android.os.Looper;
import android.view.View;
import android.webkit.WebChromeClient;
import android.webkit.WebResourceError;
import android.webkit.WebResourceRequest;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.Toast;

public class MainActivity extends Activity {

    private static final String PREFS_NAME = "VetPrefs";
    private static final String KEY_SERVER_IP = "server_ip";
    private static final String DEFAULT_SERVER_IP = "192.168.137.6";
    private static final int LOAD_TIMEOUT_MS = 10000;

    private WebView webView;
    private ProgressBar progressBar;
    private LinearLayout layoutError;
    private EditText etIpServidor;
    private Button btnReintentar;

    private final Handler timeoutHandler = new Handler(Looper.getMainLooper());
    private boolean pageLoaded = false;

    private final Runnable timeoutRunnable = new Runnable() {
        @Override
        public void run() {
            if (!pageLoaded) {
                webView.stopLoading();
                mostrarErrorConexion();
            }
        }
    };

    @SuppressLint("SetJavaScriptEnabled")
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        webView = (WebView) findViewById(R.id.webView);
        progressBar = (ProgressBar) findViewById(R.id.progressBar);
        layoutError = (LinearLayout) findViewById(R.id.layoutError);
        etIpServidor = (EditText) findViewById(R.id.etIpServidor);
        btnReintentar = (Button) findViewById(R.id.btnReintentar);

        WebSettings webSettings = webView.getSettings();
        webSettings.setJavaScriptEnabled(true);
        webSettings.setDomStorageEnabled(true);
        webSettings.setDatabaseEnabled(true);
        webSettings.setUseWideViewPort(true);
        webSettings.setLoadWithOverviewMode(true);
        webSettings.setSupportZoom(false);
        webSettings.setBuiltInZoomControls(false);

        webView.setWebViewClient(new WebViewClient() {
            @Override
            public boolean shouldOverrideUrlLoading(WebView view, WebResourceRequest request) {
                String url = request.getUrl().toString();

                if (url.startsWith("https://api.whatsapp.com") || url.startsWith("whatsapp:") || url.startsWith("tel:")) {
                    try {
                        Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse(url));
                        startActivity(intent);
                        return true;
                    } catch (Exception e) {
                        Toast.makeText(MainActivity.this, "No se encontró la aplicación para abrir el enlace", Toast.LENGTH_SHORT).show();
                        return true;
                    }
                }

                view.loadUrl(url);
                return true;
            }

            @Override
            public void onPageStarted(WebView view, String url, Bitmap favicon) {
                super.onPageStarted(view, url, favicon);
                pageLoaded = false;
                layoutError.setVisibility(View.GONE);
                webView.setVisibility(View.VISIBLE);
                progressBar.setVisibility(View.VISIBLE);
                timeoutHandler.removeCallbacks(timeoutRunnable);
                timeoutHandler.postDelayed(timeoutRunnable, LOAD_TIMEOUT_MS);
            }

            @Override
            public void onPageFinished(WebView view, String url) {
                super.onPageFinished(view, url);
                pageLoaded = true;
                timeoutHandler.removeCallbacks(timeoutRunnable);
                progressBar.setVisibility(View.GONE);
            }

            @Override
            public void onReceivedError(WebView view, WebResourceRequest request, WebResourceError error) {
                super.onReceivedError(view, request, error);
                if (request.isForMainFrame()) {
                    pageLoaded = true;
                    timeoutHandler.removeCallbacks(timeoutRunnable);
                    mostrarErrorConexion();
                }
            }
        });

        webView.setWebChromeClient(new WebChromeClient() {
            public void onProgressChanged(WebView view, int newProgress) {
                super.onProgressChanged(view, newProgress);
                if (newProgress < 100 && layoutError.getVisibility() != View.VISIBLE) {
                    progressBar.setVisibility(View.VISIBLE);
                    progressBar.setProgress(newProgress);
                } else {
                    progressBar.setVisibility(View.GONE);
                }
            }
        });

        btnReintentar.setOnClickListener(new View.OnClickListener() {
            public void onClick(View v) {
                String nuevaIp = etIpServidor.getText().toString().trim();
                if (nuevaIp.isEmpty()) {
                    Toast.makeText(MainActivity.this, "Ingresa la IP de tu PC", Toast.LENGTH_SHORT).show();
                    return;
                }
                guardarIpServidor(nuevaIp);
                cargarServidor();
            }
        });

        cargarServidor();
    }

    private String obtenerIpGuardada() {
        SharedPreferences prefs = getSharedPreferences(PREFS_NAME, MODE_PRIVATE);
        return prefs.getString(KEY_SERVER_IP, DEFAULT_SERVER_IP);
    }

    private void guardarIpServidor(String ip) {
        SharedPreferences prefs = getSharedPreferences(PREFS_NAME, MODE_PRIVATE);
        prefs.edit().putString(KEY_SERVER_IP, ip).apply();
    }

    private String construirUrl(String ipOUrl) {
        if (ipOUrl.startsWith("http://") || ipOUrl.startsWith("https://")) {
            return ipOUrl;
        }
        return "http://" + ipOUrl + "/veterinaria";
    }

    private void cargarServidor() {
        String ip = obtenerIpGuardada();
        etIpServidor.setText(ip);
        layoutError.setVisibility(View.GONE);
        webView.setVisibility(View.VISIBLE);
        webView.loadUrl(construirUrl(ip));
    }

    private void mostrarErrorConexion() {
        progressBar.setVisibility(View.GONE);
        webView.setVisibility(View.GONE);
        etIpServidor.setText(obtenerIpGuardada());
        layoutError.setVisibility(View.VISIBLE);
    }

    @Override
    protected void onDestroy() {
        timeoutHandler.removeCallbacks(timeoutRunnable);
        super.onDestroy();
    }

    @Override
    public void onBackPressed() {
        if (webView.getVisibility() == View.VISIBLE && webView.canGoBack()) {
            webView.goBack();
        } else {
            super.onBackPressed();
        }
    }
}
