import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, Spinner } from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';
import { useState, useEffect } from '@wordpress/element';
import './editor.scss';

export default function Edit({ attributes, setAttributes, clientId }) {
    const { message } = attributes;
    const blockProps = useBlockProps({ id: `whatsapp-block-${clientId}` });
    const [dynamicSettings, setDynamicSettings] = useState(null);
    const [error, setError] = useState(null);
    const [isHovered, setIsHovered] = useState(false);

    useEffect(() => {
        apiFetch({ path: '/wsa/v1/settings' })
            .then((data) => {
                setDynamicSettings(data);
            })
            .catch((err) => {
                console.error("WSA API Error:", err);
                setError(err);
            });
    }, []);

    if (error) return <p>Error loading settings. Check console.</p>;
    if (!dynamicSettings) return <Spinner />;

    return (
        <div {...blockProps}>
            <InspectorControls>
                <PanelBody title={__('WhatsApp Settings', 'what-snap-app')}>
                    <TextControl
                        label={__('Message', 'what-snap-app')}
                        value={message}
                        onChange={(val) => setAttributes({ message: val })}
                    />
                </PanelBody>
            </InspectorControls>

            <div className="what-snap-app-button-preview">
                {(() => {
                    const baseShadow = dynamicSettings.box_shadow === '1'
                        ? `${dynamicSettings.box_shadow_x}px ${dynamicSettings.box_shadow_y}px 18px rgba(0,0,0,0.18)`
                        : 'none';
                    const hoverShadow = dynamicSettings.hover_box_shadow === '1'
                        ? `${dynamicSettings.hover_box_shadow_x}px ${dynamicSettings.hover_box_shadow_y}px 24px rgba(0,0,0,0.25)`
                        : baseShadow;
                    const currentShadow = isHovered ? hoverShadow : baseShadow;
                    const currentTransform = isHovered && dynamicSettings.hover_box_shadow === '1' ? 'translateY(-2px)' : 'translateY(0)';

                    return (
                        <a
                            href="#"
                            className="whatsapp-btn editor-hover-preview"
                            onClick={(e) => e.preventDefault()}
                            onMouseEnter={() => setIsHovered(true)}
                            onMouseLeave={() => setIsHovered(false)}
                            onMouseOver={() => setIsHovered(true)}
                            onMouseOut={() => setIsHovered(false)}
                            style={{
                                backgroundColor: dynamicSettings.bg_color,
                                color: dynamicSettings.text_color,
                                borderRadius: dynamicSettings.border_radius + 'px',
                                boxShadow: currentShadow,
                                fontFamily: dynamicSettings.font_family,
                                fontWeight: dynamicSettings.font_weight,
                                transition: 'transform 0.2s ease, box-shadow 0.2s ease',
                                transform: currentTransform,
                                cursor: 'pointer',
                                '--wsa-hover-shadow': hoverShadow,
                            }}
                        >
                            <span className="whatsapp-icon-wrapper">
                                <svg className="whatsapp-icon" style={{ width: dynamicSettings.icon_size + 'px', height: dynamicSettings.icon_size + 'px' }} viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        fillRule="evenodd"
                                        clipRule="evenodd"
                                        d="M29.9913 0C13.4528 0 0 13.4566 0 29.9997C0 36.5605 2.1158 42.645 5.71251 47.5837L1.97449 58.7297L13.5056 55.0444C18.2482 58.1837 23.908 60 30.0087 60C46.5472 60 60 46.5429 60 30.0003C60 13.4571 46.5472 0.000495911 30.0087 0.000495911L29.9913 0ZM21.6161 15.2385C21.0344 13.8453 20.5935 13.7926 19.7122 13.7568C19.4122 13.7393 19.0778 13.7219 18.707 13.7219C17.5606 13.7219 16.3618 14.0569 15.6388 14.7976C14.7575 15.697 12.571 17.7955 12.571 22.099C12.571 26.4025 15.7095 30.5647 16.1324 31.147C16.5733 31.7284 22.251 40.6879 31.0666 44.3394C37.9604 47.1964 40.0061 46.9316 41.5751 46.5966C43.8671 46.1029 46.7413 44.4091 47.4643 42.3638C48.1873 40.3176 48.1873 38.5715 47.9753 38.2011C47.7638 37.8308 47.1816 37.6198 46.3004 37.1783C45.4191 36.7373 41.1342 34.6208 40.3231 34.3386C39.5294 34.039 38.7716 34.145 38.1725 34.9916C37.326 36.1733 36.4975 37.3729 35.8273 38.0956C35.2983 38.6601 34.4339 38.7307 33.7114 38.4306C32.7416 38.0254 30.0266 37.0722 26.6763 34.0917C24.0842 31.7817 22.3212 28.9072 21.8102 28.0431C21.2986 27.1616 21.7573 26.6494 22.1624 26.1736C22.6033 25.6265 23.0263 25.2388 23.4672 24.7271C23.9081 24.2159 24.1549 23.9511 24.437 23.3513C24.7371 22.7695 24.5251 22.1697 24.3136 21.7287C24.1021 21.2877 22.3391 16.9841 21.6161 15.2385Z"
                                        fill={dynamicSettings.icon_bg_color}
                                    />
                                </svg>
                            </span>
                            <span>{dynamicSettings.button_text}</span>
                        </a>
                    );
                })()}
            </div>
        </div>
    );
}